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
     * @var string
     */
    private $mailerHost;
    /**
     * @var string
     */
    private $mailerUser;
    /**
     * @var string
     */
    private $mailerPassword;

    /**
     * TelegramManager constructor.
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     */
    public function __construct(LoggerInterface $logger, string $mailerHost, string $mailerUser, string $mailerPassword, EntityManagerInterface $em)
    {
        $this->mailerHost = $mailerHost;
        $this->mailerUser = $mailerUser;
        $this->mailerPassword = $mailerPassword;
        $this->em = $em;
    }

    /**
     * @return array|null
     */
    public function getUpdateRaw(): ?array
    {
        return json_decode(file_get_contents("php://input"), TRUE);
    }

    /**
     * @return UpdateMetadataDto|null
     */
    public function getUpdate(): ?UpdateMetadataDto
    {
        return $this->getUpdateMetadata($this->getUpdateRaw());
    }


    /**
     *
     * @return \Swift_Mailer
     */
    public function getMailer(){
        $transport = (new \Swift_SmtpTransport($this->mailerHost , 465))
            ->setUsername($this->mailerUser)
            ->setPassword($this->mailerPassword)
            ->setEncryption('SSL');

        return new \Swift_Mailer($transport);
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
        if(!$parsedImage){
            $parsedImage = new ParsedImage($url, $createdAt, $publishedAt, $seen);
            $this->em->persist($parsedImage);
            $this->em->flush();
        }

        return $parsedImage;
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

        $tgToMessage = new TelegramMessage(null, $messageId, $this->getBotUser(), $this->getAdminUser(), (new \DateTime())->setTimestamp(time()), 'Хотите ответить?');

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
    public function createUnresolvedCommandByUser(TelegramUser $user, string $command, ?array $parameters = []) : UnresolvedCommand
    {
        $unresolvedCommand = new UnresolvedCommand(null, $user, $command, json_encode($parameters), time());
        $this->em->persist($unresolvedCommand);
        $this->em->flush();
        return $unresolvedCommand;
    }

    /**
     * @param TelegramUser $user
     * @param string $command
     * @return bool
     */
    public function validateUserCommand(TelegramUser $user, string $command) : bool
    {
        $adminsCommands = [
            UnresolvedCommand::COMMAND_REPLY
        ];
        $usersCommands = [

        ];
        if(in_array($command, $adminsCommands)){
            if($user->getUserId() === TelegramManager::CHAT_ID_ME){
                return true;
            }
        }elseif(in_array($command, $usersCommands)){
            if(!$user->getIsBot()){
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $targetId
     * @return TelegramUser
     */
    public function getUserByUserId(int $targetId) : TelegramUser
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
        if($id){
            return [true, $this->em->find(TelegramUser::class, $id)];
        }else{
            $qb = $this->em->createQueryBuilder();
            $qb->select('tgUser')->from(TelegramUser::class, 'tgUser');
            $qb->andWhere($qb->expr()->eq('tgUser.userId', $userId));
            $qb->setMaxResults(1);
            $issetUser = $qb->getQuery()->getOneOrNullResult();
            if(!$issetUser){
                $tgUser = new TelegramUser(null, intval($userId), $username, $firstname, $lastname, $isBot);
                $this->em->persist($tgUser);
                $this->em->flush();
                return [false, $tgUser];
            }else{
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
        if(!$issetMessage){
            return new TelegramMessage(null, $messageId, $this->getAdminUser(), $this->getBotUser(), (new \DateTime()), '');
        }else{
            return $issetMessage;
        }
    }

    /**
     * @param TelegramMessage $tgMessage
     */
    public function saveMessageToDB(TelegramMessage $tgMessage)
    {
        $issetMessage = $this->em->getRepository(TelegramMessage::class)->findOneBy(['messageId' => $tgMessage->getMessageId()]);
        if($issetMessage){
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
        if(!$tgMessage->getChat()){
            throw new \InvalidArgumentException('User "to" is required');
        }
        if(!$tgMessage->getFrom()){
            throw new \InvalidArgumentException('User "from" is required');
        }
        $apiUrl = 'https://api.telegram.org/bot' . ApiController::botapikey . '/sendMessage';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);

        if($inlineKeyboardMarkup){
            $body["reply_markup"] = json_encode($inlineKeyboardMarkup);
        }

        $body["chat_id"] = $tgMessage->getChat()->getUserId();
        $body["text"] = $tgMessage->getText();
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($curl);

        if ($output === false) {
            $this->throwException(curl_error($curl));
        }
        curl_close($curl);
        $tgMessage->setStatus(TelegramMessage::STATUS_SEEN);
        $this->saveMessageToDB($tgMessage);

//        if($update->getSticker()){
//            $apiUrl = 'https://api.telegram.org/bot' . ApiController::botapikey . '/sendSticker';
//
//            $curl = curl_init();
//            curl_setopt($curl, CURLOPT_URL, $apiUrl);
//            $body["chat_id"] = $toChatId;
//            $body['sticker'] = $update->getSticker();
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
//
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//
//            $output = curl_exec($curl);
//
//            if ($output === false) {
//                $this->throwException(curl_error($curl));
//            }
//        }

//        $photos = $update->getPhotos();
//        if($photos && count($photos)){
//            $apiUrl = 'https://api.telegram.org/bot' . ApiController::botapikey . '/sendMediaGroup';
//
//            $curl = curl_init();
//            curl_setopt($curl, CURLOPT_URL, $apiUrl);
//            $body["chat_id"] = $toChatId;
//            $body['media'] = $photos;
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
//
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//
//            $output = curl_exec($curl);
//
//            if ($output === false) {
//                $this->throwException(curl_error($curl));
//            }
//        }
//        $videos = $update->getVideos();
//
//        if($videos && count($videos)){
//            $apiUrl = 'https://api.telegram.org/bot' . ApiController::botapikey . '/sendMediaGroup';
//
//            $curl = curl_init();
//            curl_setopt($curl, CURLOPT_URL, $apiUrl);
//            $body["chat_id"] = $toChatId;
//            $body['media'] = $videos;
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
//
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//
//            $output = curl_exec($curl);
//
//            if ($output === false) {
//                $this->throwException(curl_error($curl));
//            }
//        }
//
//        $documents = $update->getDocuments();
//
//        if($documents && count($documents)){
//            $apiUrl = 'https://api.telegram.org/bot' . ApiController::botapikey . '/sendDocument';
//
//            foreach ($documents as $document) {
//                $curl = curl_init();
//                curl_setopt($curl, CURLOPT_URL, $apiUrl);
//                $body["chat_id"] = $toChatId;
//                $body['document'] = $document;
//
//                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
//
//                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//
//                $output = curl_exec($curl);
//
//                if ($output === false) {
//                    $this->throwException(curl_error($curl));
//                }
//            }
//        }
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
        $command = null;
        $userId = null;
        $userFirstname = null;
        $userLastname = null;
        $isForwarded = false;
        if(isset($update['message'])) {
            $message = $update['message'];
            $isForwarded = isset($updateRaw['message']['forward_from']);
        }elseif(isset($update['callback_query'])){
            if(isset($update['callback_query']['message'])){
                $message = $update['callback_query']['message'];
                $isForwarded = isset($updateRaw['callback_query']['message']['forward_from']);
            }
            if(isset($update['callback_query']['data'])){
                $command = $update['callback_query']['data'];
            }
        }

        if($message){
            $username = '@' . $message['from']['username'];
            $userFirstname = $message['from']['first_name'];
            $userLastname = $message['from']['last_name'];
            $userId = $message['from']['id'];
            $chatId = $message['chat']['id'];
            $isBot = $message['from']['is_bot'];
            $date = (new \DateTime())->setTimestamp($message['date']);
        }

        if(!$username){
            $this->throwException('Username is required');
        }

        if(!$date){
            $this->throwException('Date is required');
        }

        if(!$chatId){
            $this->throwException('Chat id is required');
        }

        list($issetUser, $user) = $this->getOrCreateUser(null, intval($userId), $username, $userFirstname, $userLastname, $isBot);
        $user->setIsBot($isBot);
        $updateMetadata = new UpdateMetadataDto($user, $date, $chatId);
        $updateMetadata->setIsForwarded($isForwarded);
        if($command){
            $updateMetadata->setType(UpdateMetadataDto::TYPE_COMMAND);
            $updateMetadata->setCommand($command);
        }else{
            $updateMetadata->setType(UpdateMetadataDto::TYPE_MESSAGE);
        }

        if(isset($message['message_id'])){
            $updateMetadata->setMessageId($message['message_id']);
        }

        if(isset($message['text'])){
            $updateMetadata->setMessageText($message['text']);
        }

        if(isset($message['photo'])){
            $photos = [];
            foreach ($message['photo'] as $item) {
                $photos[] = $item['file_id'];
            }
            $updateMetadata->setPhotos($photos);
            if(isset($message['photo']['caption'])){
                $updateMetadata->setMessageText($message['photo']['caption']);
            }
        }
        if(isset($message['sticker'])){
            $updateMetadata->setSticker($message['sticker']['file_id']);
        }
        if(isset($message['audio'])){
            $updateMetadata->setSticker($message['sticker']['file_id']);
        }

        if(isset($message['audio'])){
            $audios = [];
            foreach ($message['audio'] as $item) {
                $audios[] = $item['file_id'];
            }
            $updateMetadata->setAudios($audios);
        }
        if(isset($message['video'])){
            $videos = [];
            foreach ($message['video'] as $item) {
                $videos[] = $item['file_id'];
            }
            $updateMetadata->setVideos($videos);
        }
        if(isset($message['animation'])){
            $animations = [];
            foreach ($message['animation'] as $item) {
                $animations[] = $item['file_id'];
            }
            $updateMetadata->setAnimations($animations);
        }
        if(isset($message['document'])){
            $documents = [];
            foreach ($message['document'] as $item) {
                $documents[] = $item['file_id'];
            }
            $updateMetadata->setDocuments($documents);
        }

        return $updateMetadata;
    }

    public function throwException(string $message){
        $this->notifyAdmins($message);
        throw new BadRequestHttpException($message);
    }

    public function getBotUser():TelegramUser
    {
        return $this->em->find(TelegramUser::class, 1);
    }

    public function getAdminUser():TelegramUser
    {
        return $this->em->find(TelegramUser::class, 2);
    }

    public function notifyAdmins($messageText){
        $mailer = $this->getMailer();
        $message = new \Swift_Message('Exception');
        $message->setFrom($this->mailerUser);
        $message->setTo(['ukrs69@gmail.com' => 'Ilya']);
        $message->setBody($messageText);
        $mailer->send($message);
    }

    /**
     * @param TelegramMessage $telegramMessage
     * @return array|null
     */
    public function getCommandsFromMessage(TelegramMessage $telegramMessage): ?array
    {
        $result = [];
        if($telegramMessage->getText()){
            foreach (UnresolvedCommand::getAllowedCommands() as $allowedCommand) {
                $result[$allowedCommand] = stripos($telegramMessage->getText(), $allowedCommand);
            }
        }

        return $result;
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
            if($position > $prevPos){
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
