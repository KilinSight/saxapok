<?php

namespace AppBundle\Manager;

use AppBundle\Controller\ApiController;
use AppBundle\Entity\ParsedImage;
use AppBundle\Entity\TelegramMessage;
use AppBundle\Entity\TelegramUser;
use AppBundle\Entity\UnresolvedCommand;
use AppBundle\Entity\UpdateMetadataDto;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function Doctrine\ORM\QueryBuilder;

class TelegramManager
{


    const CHAT_ID_ME = 328438276;
    const CHAT_ID_NATASHA = 334733456;
    const CHAT_ID_MAX = 348558502;
    const CHAT_ID_BOT = 914200924;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * @var TelegramUser
     */
    private $userBot;

    /**
     * @var TelegramUser
     */
    private $userAdmin;

    /**
     * @var string
     */
    private $updateRaw;

    /**
     * @var UpdateMetadataDto
     */
    private $update;

    /**
     * TelegramManager constructor.
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, MailerService $mailerService)
    {
        $this->em = $em;
        $this->mailerService = $mailerService;
        $this->userBot = $this->em->find(TelegramUser::class, 2);
        $this->userAdmin = $this->em->find(TelegramUser::class, 1);
    }

    /**
     * @return array|null
     */
    public function getUpdateRaw(): ?array
    {
        return $this->updateRaw = json_decode(file_get_contents("php://input"), TRUE);
    }

    /**
     * @return UpdateMetadataDto|null
     */
    public function getUpdate(): ?UpdateMetadataDto
    {
        return $this->getUpdateMetadata($this->getUpdateRaw());
    }

    /**
     * @param string $url
     * @param int|null $createdAt
     * @param int|null $publishedAt
     * @param bool|null $seen
     * @return ParsedImage
     */
    public function getOrCreateParsedImage(string $url, ?int $createdAt = null, ?int $publishedAt = null, ?bool $seen = false): ParsedImage
    {
        $parsedImage = $this->em->getRepository(ParsedImage::class)->findOneBy(['url' => $url]);
        if (!$parsedImage) {
            $parsedImage = new ParsedImage($url, $createdAt, $publishedAt, $seen);
            $this->em->persist($parsedImage);
            $this->em->flush();
        }

        return $parsedImage;
    }

    /**
     * @param TelegramMessage $tgFromMessage
     * @return void
     */
    public function sendToAdmin(TelegramMessage $tgFromMessage)
    {
        $keyboard = [
            [
                [
                    'text' => 'Reply Now', 'callback_data' => "/reply_" . $tgFromMessage->getFrom()->getUserId()
                ]
            ]
        ];
        $inlineKeyboardMarkup = [
            'inline_keyboard' => $keyboard
        ];

        $tgFromMessage->setFrom($this->getBotUser());
        $tgFromMessage->setChat($this->getAdminUser());
        $this->sendMessageTo($tgFromMessage);

        $tgToMessage = new TelegramMessage($tgFromMessage->getMessageId(), $this->getBotUser(), $this->getAdminUser(), (new \DateTime())->setTimestamp(time()), 'Хотите ответить?');
        $this->sendMessageTo($tgToMessage, $inlineKeyboardMarkup);
    }

    /**
     * @param string $from
     * @param string $messageId
     * @return void
     * @throws \Exception
     */
    public function forwardToAdmin(string $from, string $messageId)
    {
        $apiUrl = 'https://api.telegram.org/bot' . ApiController::botapikey . '/forwardMessage';

        $body["chat_id"] = self::CHAT_ID_ME;
        $body["from_chat_id"] = $from;
        $body["message_id"] = $messageId;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($curl);

        if ($output === false) {
            $this->throwException(curl_error($curl));
        }

        $tgToMessage = new TelegramMessage($messageId, $this->getBotUser(), $this->getAdminUser(), (new \DateTime())->setTimestamp(time()), 'Хотите ответить?');

        $keyboard = [
            [
                [
                    'text' => 'Reply Now', 'callback_data' => "/reply_" . $from
                ]
            ]
        ];
        $inlineKeyboardMarkup = [
            'inline_keyboard' => $keyboard
        ];
        $this->sendMessageTo($tgToMessage, $inlineKeyboardMarkup);
    }

    /**
     * @param TelegramUser $user
     * @return UnresolvedCommand
     */
    public function deleteAllUnresolvedCommandByUser(TelegramUser $user)
    {

        $qb = $this->em->createQueryBuilder();
        $qb->delete(UnresolvedCommand::class, 'unresolvedCommand');
        $qb->andWhere($qb->expr()->eq('unresolvedCommand.user', $user->getId()));

        return $qb->getQuery()->execute();;
    }

    /**
     * @param TelegramUser $user
     * @param string $command
     * @param array|null $parameters
     * @return UnresolvedCommand
     * @throws \Exception
     */
    public function createUnresolvedCommandByUser(TelegramUser $user, string $command, ?array $parameters = []): UnresolvedCommand
    {
        $unresolvedCommand = new UnresolvedCommand($user, $command, json_encode($parameters), time());
        $this->em->persist($unresolvedCommand);
        $this->em->flush();
        return $unresolvedCommand;
    }

    /**
     * @param TelegramUser $user
     * @param string $command
     * @return bool
     */
    public function validateUserCommand(TelegramUser $user, string $command): bool
    {
        $adminsCommands = [
            UnresolvedCommand::COMMAND_REPLY
        ];
        $usersCommands = [

        ];
        if (in_array($command, $adminsCommands)) {
            if ($user->getUserId() === TelegramManager::CHAT_ID_ME) {
                return true;
            }
        } elseif (in_array($command, $usersCommands)) {
            if (!$user->getIsBot()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $targetId
     * @return TelegramUser
     */
    public function getUserByUserId(int $targetId): TelegramUser
    {
        return $this->em->getRepository(TelegramUser::class)->findOneBy(['userId' => $targetId]);
    }

    /**
     * @param int|null $id
     * @param int $userId
     * @param string $username
     * @param string|null $firstname
     * @param string|null $lastname
     * @param bool|null $isBot
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOrCreateUser(?int $id = null, int $userId, string $username, ?string $firstname = null, ?string $lastname = null, ?bool $isBot = false)
    {
        if ($id) {
            return [true, $this->em->find(TelegramUser::class, $id)];
        } else {
            $qb = $this->em->createQueryBuilder();
            $qb->select('tgUser')->from(TelegramUser::class, 'tgUser');
            $qb->andWhere($qb->expr()->eq('tgUser.userId', $userId));
            $qb->setMaxResults(1);
            $issetUser = $qb->getQuery()->getOneOrNullResult();
            if (!$issetUser) {
                $tgUser = new TelegramUser(null, intval($userId), $username, $firstname, $lastname, $isBot);
                $this->em->persist($tgUser);
                $this->em->flush();
                return [false, $tgUser];
            } else {
                return [true, $issetUser];
            }
        }
    }

    /**
     * @param int $messageId
     * @return TelegramMessage
     * @throws \Exception
     */
    public function getOrCreateMessage(int $messageId): TelegramMessage
    {
        $issetMessage = $this->em->getRepository(TelegramMessage::class)->findOneBy(['messageId' => $messageId]);
        if (!$issetMessage) {
            return new TelegramMessage($messageId, $this->getAdminUser(), $this->getBotUser(), (new \DateTime()), '');
        } else {
            return $issetMessage;
        }
    }

    /**
     * @param TelegramMessage $tgMessage
     */
    public function saveMessageToDB(TelegramMessage $tgMessage)
    {
        $issetMessage = $this->em->getRepository(TelegramMessage::class)->findOneBy(['messageId' => $tgMessage->getMessageId()]);
        if ($issetMessage) {
            $tgMessage->setId($issetMessage->getId());
        }

        $this->em->persist($tgMessage);
        $this->em->flush();
    }

    /**
     * @param TelegramMessage $tgMessage
     * @param array|null $inlineKeyboardMarkup
     */
    public function sendMessageTo(TelegramMessage $tgMessage, ?array $inlineKeyboardMarkup = null)
    {
        if (!$tgMessage->getChat()) {
            throw new \InvalidArgumentException('User "to" is required');
        }
        if (!$tgMessage->getFrom()) {
            throw new \InvalidArgumentException('User "from" is required');
        }
        $method = '/sendMessage';

        $curl = curl_init();

        $bodies = [];
        $body = [];
        if($tgMessage->getPhoto()){
            $method = '/sendPhoto';

            $photos = explode(',', $tgMessage->getPhoto());
            if(count($photos) > 1){
                $medias = [];
                foreach ($photos as $photo) {
                    $medias[] = [
                        'type' => 'photo',
                        'media' => $photo,
                        'caption' => $tgMessage->getText()
                    ];
                }
                $method = '/sendMediaGroup';

                $body['media'] = $medias;
            }else{
                $body['photo'] = $photos[0];
                if($tgMessage->getText()){
                    $body['caption'] = $tgMessage->getText();
                }
            }
        }elseif ($tgMessage->getVideo()){
            $method = '/sendVideo';

            $videos = explode(',', $tgMessage->getPhoto());
            if(count($videos) > 1){
                $medias = [];
                foreach ($videos as $video) {
                    $medias[] = [
                        'type' => 'video',
                        'media' => $video,
                        'caption' => $tgMessage->getText()
                    ];
                }
                $method = '/sendMediaGroup';

                $body['media'] = $medias;
            }else{
                $body['video'] = $videos[0];
                if($tgMessage->getText()){
                    $body['caption'] = $tgMessage->getText();
                }
            }
        }elseif ($tgMessage->getAudio()){
            $method = '/sendAudio';

            $bodies = $this->getBodiesByType('audio', $tgMessage);
        }elseif ($tgMessage->getSticker()){
            $method = '/sendSticker';

            $body['sticker'] = $tgMessage->getSticker();
        }elseif ($tgMessage->getAnimation()){
            $method = '/sendAnimation';

            $bodies = $this->getBodiesByType('animation', $tgMessage);
        }elseif ($tgMessage->getDocument()){
            $method = '/sendDocument';

            $bodies = $this->getBodiesByType('document', $tgMessage);
        }elseif ($tgMessage->getVoice()){
             $method = '/sendVoice';

            $bodies = $this->getBodiesByType('voice', $tgMessage);
        }

        if(!count($bodies) && !empty($body)) {
            $bodies[] = $body;
        }

        $apiUrl = 'https://api.telegram.org/bot' . ApiController::botapikey . $method;
        curl_setopt($curl, CURLOPT_URL, $apiUrl);

        foreach ($bodies as $body) {
            if ($inlineKeyboardMarkup) {
                $body["reply_markup"] = json_encode($inlineKeyboardMarkup);
            }

            $body["chat_id"] = $tgMessage->getChat()->getUserId();

            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $output = curl_exec($curl);

            if ($output === false) {
                $this->throwException(curl_error($curl));
            }
        }

        curl_close($curl);
        $tgMessage->setStatus(TelegramMessage::STATUS_SEEN);
        $this->saveMessageToDB($tgMessage);
    }

    /**
     * @param string $type
     * @param TelegramMessage $tgMessage
     * @return array
     */
    private function getBodiesByType(string $type, TelegramMessage $tgMessage): array
    {
        $body = [];
        $bodies = [];
        $items = [];
        if($type === 'animation'){
            $items = explode(',', $tgMessage->getAnimation());
        }elseif($type === 'audio'){
            $items = explode(',', $tgMessage->getAudio());
        }elseif($type === 'document'){
            $items = explode(',', $tgMessage->getDocument());
        }elseif($type === 'voice'){
            $items = explode(',', $tgMessage->getVoice());
        }
        foreach ($items as $item) {
            $body[$type] = $item;
            if($tgMessage->getText()){
                $body['caption'] = $tgMessage->getText();
            }
            $bodies[] = $body;
        }

        return $bodies;
    }

    /**
     * @param UpdateMetadataDto $update
     * @return array|null
     */
    public function getMessageByUpdate(UpdateMetadataDto $update): ?array
    {
        $message = [];

        return $message;
    }

    /**
     * @param array $update
     * @return array|null
     */
    public function getUpdateMetadata(array $update): ?UpdateMetadataDto
    {
        $updateMetadata = null;
        $username = null;
        $date = null;
        $chatId = null;
        $message = null;
        $tgMessage = null;
        $command = null;
        $userId = null;
        $userFirstname = null;
        $userLastname = null;
        $isForwarded = false;
        if (isset($update['message'])) {
            $message = $update['message'];
            $isForwarded = isset($updateRaw['message']['forward_from']);
        } elseif (isset($update['edited_message'])) {
            $message = $update['edited_message'];
            $isForwarded = isset($updateRaw['message']['forward_from']);
        } elseif (isset($update['callback_query'])) {
            if (isset($update['callback_query']['message'])) {
                $message = $update['callback_query']['message'];
                $isForwarded = isset($updateRaw['callback_query']['message']['forward_from']);
            }
            if (isset($update['callback_query']['data'])) {
                $command = $update['callback_query']['data'];
            }
        }

        if ($message) {
            $username = $message['from']['username'];
            $userFirstname = $message['from']['first_name'];
            $userLastname = $message['from']['last_name'];
            $userId = $message['from']['id'];
            $isBot = $message['from']['is_bot']?true:false;
            $date = (new \DateTime())->setTimestamp($message['date']);

            $chatId = $message['chat']['id'];
            $toUsername = $message['chat']['username'];
            $toUserFirstname = $message['chat']['first_name'];
            $toUserLastname = $message['chat']['last_name'];
            $toUserIsBot = $message['chat']['is_bot']?true:false;
            if (!$username) {
                $this->notifyAdmins('Username is required');
                return null;
            }

            if (!$date) {
                $this->notifyAdmins('Date is required');
                return null;
            }

            if (!$chatId) {
                $this->notifyAdmins('Chat id is required');
                return null;
            }

            list($issetUser, $user) = $this->getOrCreateUser(null, intval($userId), $username, $userFirstname, $userLastname, $isBot);
            /** @var TelegramUser $toUser */
            list($issetToUser, $toUser) = $this->getOrCreateUser(null, intval($chatId), $toUsername, $toUserFirstname, $toUserLastname, $toUserIsBot);

            $user->setIsBot($isBot);
            $toUser->setIsBot($toUserIsBot);

            $tgMessage = $this->getOrCreateMessage($message['message_id']);
            $tgMessage->setFrom($user);
            $tgMessage->setChat($toUser);
            $tgMessage->setDate($date);
            $tgMessage->setText($message['text']);
            $tgMessage->setIsForwarded($isForwarded);
        }

        $updateMetadata = new UpdateMetadataDto($user, $date, $chatId);
        if ($command) {
            $updateMetadata->setType(UpdateMetadataDto::TYPE_COMMAND);
            $updateMetadata->setCommand($command);
        } else {
            $updateMetadata->setType(UpdateMetadataDto::TYPE_MESSAGE);
        }

        if (isset($message['photo'])) {
            $photos = [];
            foreach ($message['photo'] as $item) {
                $photos[] = $item['file_id'];
            }
            $tgMessage->setPhoto(join(',', $photos));
            if (isset($message['photo']['caption'])) {
                $tgMessage->setText($message['photo']['caption']);
            }
        }
        if (isset($message['sticker'])) {
            $tgMessage->setSticker($message['sticker']['file_id']);
        }

        if (isset($message['audio'])) {
            $audios = [];
            foreach ($message['audio'] as $item) {
                $audios[] = $item['file_id'];
            }
            $tgMessage->setAudio(join(',', $audios));
        }
        if (isset($message['video'])) {
            $videos = [];
            foreach ($message['video'] as $item) {
                $videos[] = $item['file_id'];
            }
            $tgMessage->setVideo(join(',', $videos));
        }
        if (isset($message['animation'])) {
            $animations = [];
            foreach ($message['animation'] as $item) {
                $animations[] = $item['file_id'];
            }
            $tgMessage->setAnimation(join(',', $animations));
        }
        if (isset($message['document'])) {
            $documents = [];
            foreach ($message['document'] as $item) {
                $documents[] = $item['file_id'];
            }
            $tgMessage->setDocument(join(',', $documents));
        }
        if($tgMessage){
            $this->saveMessageToDB($tgMessage);
            $updateMetadata->setMessage($tgMessage);
        }
        $this->update = $updateMetadata;
        return $updateMetadata;
    }

    public function throwException(string $message)
    {
        $this->notifyAdmins($message);
        throw new BadRequestHttpException($message);
    }

    public function getBotUser(): TelegramUser
    {
        return $this->userBot;
    }

    public function getAdminUser(): TelegramUser
    {
        return $this->userAdmin;
    }

    public function notifyAdmins($messageText)
    {
        $message = new \Swift_Message('Exception');
        $message->setTo(['ukrs69@gmail.com' => 'Ilya']);
        $message->setBody($messageText);
        $this->mailerService->sendMessageTo($message);
    }

    /**
     * @param TelegramMessage $telegramMessage
     * @return array|null
     */
    public function getCommandsFromMessage(TelegramMessage $telegramMessage): ?array
    {
        $result = [];
        if ($telegramMessage->getText()) {
            foreach (UnresolvedCommand::getAllowedCommands() as $allowedCommand) {
                $result[$allowedCommand] = stripos($telegramMessage->getText(), $allowedCommand);
            }
        }

        return $result;
    }

    /**
     * @param TelegramMessage $telegramMessage
     * @return TelegramMessage
     * @throws \Exception
     */
    public function processCommandFromMessage(TelegramMessage $telegramMessage): TelegramMessage
    {
        $em = $this->em;
        $userAdmin = $this->getAdminUser();
        $userBot = $this->getBotUser();

        list($command, $targetId) = explode('_', $this->update->getCommand());
        if ($command === UnresolvedCommand::COMMAND_REPLY) {
            if ($this->validateUserCommand($telegramMessage->getChat(), $command)) {
                $targetUser = $this->getUserByUserId($targetId);
                if ($targetUser) {
                    $parameters = ['reply_to' => $targetUser->getUserId()];
                    $this->createUnresolvedCommandByUser($userAdmin, $command, $parameters);
                    $tgToMessage = new TelegramMessage($this->update->getMessageId(), $userBot, $userAdmin, $this->update->getDate(), "Введите текст ответа пользователю @" . $targetUser->getUsername());
                    $this->sendMessageTo($tgToMessage);
                }
            }
        } elseif ($command === UnresolvedCommand::COMMAND_START) {
            $telegramMessage->setStatus(TelegramMessage::STATUS_SEEN);
        } elseif ($command === UnresolvedCommand::COMMAND_CANCEL) {
            $telegramMessage->setStatus(TelegramMessage::STATUS_SEEN);
        }

        return $telegramMessage;
    }

    /**
     * @param TelegramMessage $telegramMessage
     * @return void
     * @throws \Exception
     */
    public function processUnresolvedCommandsFromMessage(TelegramMessage $telegramMessage): void
    {
        $em = $this->em;
        $userAdmin = $this->getAdminUser();
        $userBot = $this->getBotUser();

        foreach ($telegramMessage->getFrom()->getUnresolvedCommands() as $unresolvedCommand) {
            if ($unresolvedCommand->getDate() < time() - 30) {
                if ($unresolvedCommand->getCommand() !== UnresolvedCommand::COMMAND_DEBUG) {
                    $em->remove($unresolvedCommand);
                }
                continue;
            }
            if ($unresolvedCommand->getCommand() === UnresolvedCommand::COMMAND_REPLY) {
                $parameters = json_decode($unresolvedCommand->getParameters(), true);
                $replyToUser = $this->getUserByUserId($parameters['reply_to']);
                $replyMessage = new TelegramMessage($telegramMessage->getMessageId(), $userBot, $replyToUser, $telegramMessage->getDate(), $telegramMessage->getText());
                $this->sendMessageTo($replyMessage);
                $em->remove($unresolvedCommand);
            } elseif ($unresolvedCommand->getCommand() === UnresolvedCommand::COMMAND_DEBUG) {
                $debugMessage = new TelegramMessage($telegramMessage->getMessageId(), $userBot, $userAdmin, $telegramMessage->getDate(), json_encode($this->updateRaw));
                $this->sendMessageTo($debugMessage);
            }
        }
    }

    /**
     * @param TelegramMessage $telegramMessage
     * @return void
     * @throws \Exception
     */
    public function processLastCommandFromMessage(TelegramMessage $telegramMessage): void
    {
        $em = $this->em;
        $userAdmin = $this->getAdminUser();
        $userBot = $this->getBotUser();
        $em = $this->em;
        $lastCommand = $this->getLastCommandFromMessage($telegramMessage);
        if ($lastCommand) {
            if ($lastCommand === UnresolvedCommand::COMMAND_DEBUG) {
                $issetDebug = $em->getRepository(UnresolvedCommand::class)->findOneBy(['command' => UnresolvedCommand::COMMAND_DEBUG]);
                if (!$issetDebug) {
                    $this->createUnresolvedCommandByUser($userAdmin, UnresolvedCommand::COMMAND_DEBUG, []);

                    $notifyMessage = new TelegramMessage($telegramMessage->getMessageId(), $userBot, $userAdmin, $telegramMessage->getDate(), 'Debug mode ON');
                    $this->sendMessageTo($notifyMessage);
                } else {
                    $notifyMessage = new TelegramMessage($telegramMessage->getMessageId(), $userBot, $userAdmin, $telegramMessage->getDate(), 'Debug mode already inited');
                    $this->sendMessageTo($notifyMessage);
                }

            } elseif ($lastCommand === UnresolvedCommand::COMMAND_STOP) {
                $this->deleteAllUnresolvedCommandByUser($telegramMessage->getFrom());

                $notifyMessage = new TelegramMessage($telegramMessage->getMessageId(), $userBot, $telegramMessage->getFrom(), $telegramMessage->getDate(), 'All active commands was disabled');
                $this->sendMessageTo($notifyMessage);
            } elseif ($lastCommand === UnresolvedCommand::COMMAND_MENU) {
                $this->deleteAllUnresolvedCommandByUser($telegramMessage->getFrom());

                $notifyMessage = new TelegramMessage($telegramMessage->getMessageId(), $userBot, $telegramMessage->getFrom(), $telegramMessage->getDate(), 'All active commands was disabled');
                $this->sendMessageTo($notifyMessage);
            }
        }
    }

    /**
     * @param TelegramMessage $telegramMessage
     * @return string|null
     */
    public function getLastCommandFromMessage(TelegramMessage $telegramMessage): ?string
    {
        $result = null;
        $commands = $this->getCommandsFromMessage($telegramMessage);
        $prevPos = -1;
        foreach ($commands as $allowedCommand => $position) {
            if ($position > $prevPos) {
                $result = $allowedCommand;
                $prevPos = $position;
            }
        }

        return $result;
    }

    /**
     * @return array|null
     */
    public function getNotSeenParsedImage(): ?array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('parsedImage')->from(ParsedImage::class, 'parsedImage');
        $qb->andWhere($qb->expr()->eq('parsedImage.seen', 0));
        $parsedImages = $qb->getQuery()->getResult();

        return $parsedImages;
    }
}
