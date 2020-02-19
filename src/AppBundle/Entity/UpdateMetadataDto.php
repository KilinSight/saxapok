<?php
namespace AppBundle\Entity;


class UpdateMetadataDto
{


    const TYPE_COMMAND = 'command';
    const TYPE_MESSAGE = 'message';
    /**
     * @var TelegramUser
     */
    private $user;

    /**
     * UpdateMetadataDto constructor.
     * @param TelegramUser $user
     * @param \DateTime $date
     * @param string $chatId
     * @param string $messageText
     * @param string|null $messageId
     * @param string $type
     * @param string[] $photos
     * @param string[] $animations
     * @param string[] $videos
     * @param string $sticker
     * @param string[] $audios
     * @param string[] $documents
     */
    public function __construct(TelegramUser $user, \DateTime $date, string $chatId, ?string $messageText = null, ?string $messageId = null, ?string $type = null, ?array $photos = [], ?array $animations = [], ?array $videos = [], ?string $sticker = null, ?array $audios = [], ?array $documents = [])
    {
        $this->user = $user;
        $this->date = $date;
        $this->chatId = $chatId;
        $this->messageId = $messageId;
        $this->messageText = $messageText;
        $this->type = $type;
        $this->photos = $photos;
        $this->animations = $animations;
        $this->videos = $videos;
        $this->sticker = $sticker;
        $this->audios = $audios;
        $this->documents = $documents;
        $this->isForwarded = false;
    }

    /**
     * @return TelegramUser
     */
    public function getUser(): TelegramUser
    {
        return $this->user;
    }

    /**
     * @param TelegramUser $user
     */
    public function setUser(TelegramUser $user): void
    {
        $this->user = $user;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getChatId(): int
    {
        return $this->chatId;
    }

    /**
     * @param int $chatId
     */
    public function setChatId(int $chatId): void
    {
        $this->chatId = $chatId;
    }

    /**
     * @return string
     */
    public function getMessageText(): string
    {
        return $this->messageText;
    }

    /**
     * @param string $messageText
     */
    public function setMessageText(string $messageText): void
    {
        $this->messageText = $messageText;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string[]
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @param string[] $photos
     */
    public function setPhotos(array $photos): void
    {
        $this->photos = $photos;
    }

    /**
     * @return string[]
     */
    public function getAnimations(): array
    {
        return $this->animations;
    }

    /**
     * @param string[] $animations
     */
    public function setAnimations(array $animations): void
    {
        $this->animations = $animations;
    }

    /**
     * @return string[]
     */
    public function getVideos(): array
    {
        return $this->videos;
    }

    /**
     * @param string[] $videos
     */
    public function setVideos(array $videos): void
    {
        $this->videos = $videos;
    }

    /**
     * @return string
     */
    public function getSticker(): string
    {
        return $this->sticker;
    }

    /**
     * @param string $sticker
     */
    public function setSticker(string $sticker): void
    {
        $this->sticker = $sticker;
    }

    /**
     * @return string[]
     */
    public function getAudios(): array
    {
        return $this->audios;
    }

    /**
     * @param string[] $audios
     */
    public function setAudios(array $audios): void
    {
        $this->audios = $audios;
    }

    /**
     * @return string[]
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    /**
     * @param string[] $documents
     */
    public function setDocuments(array $documents): void
    {
        $this->documents = $documents;
    }

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $chatId;

    /**
     * @var boolean
     */
    private $isForwarded;

    /**
     * @var string
     */
    private $messageText;

    /**
     * @var integer
     */
    private $messageId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $command;

    /**
     * @var string[]
     */
    private $photos;
    /**
     * @var string[]
     */
    private $animations;

    /**
     * @var string[]
     */
    private $videos;

    /**
     * @var string
     */
    private $sticker;

    /**
     * @var string[]
     */
    private $audios;

    /**
     * @var string[]
     */
    private $documents;

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     */
    public function setMessageId(int $messageId): void
    {
        $this->messageId = $messageId;
    }

    /**
     * @return bool
     */
    public function isForwarded(): bool
    {
        return $this->isForwarded;
    }

    /**
     * @param bool $isForwarded
     */
    public function setIsForwarded(bool $isForwarded): void
    {
        $this->isForwarded = $isForwarded;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }


}