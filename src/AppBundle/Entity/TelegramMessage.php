<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="telegram_message")
 */
class TelegramMessage
{
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_SEEN = 'seen';
    const STATUS_CANCELED = 'canceled';

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", length=11, unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(name="message_id", type="integer", length=11, nullable=false)
     * @var integer
     */
    private $messageId;

    /**
     * @ORM\ManyToOne(targetEntity="TelegramUser")
     * @ORM\JoinColumn(name="user_from", referencedColumnName="id")
     *
     * @var TelegramUser
     */
    private $from;

    /**
     * @ORM\ManyToOne(targetEntity="TelegramUser")
     * @ORM\JoinColumn(name="user_to", referencedColumnName="id")
     *
     * @var TelegramUser
     */
    private $chat;

    /**
     * @ORM\Column(name="date", type="integer", length=11, nullable=true)
     * @var \DateTime
     */
    private $date;

    /**
     * @ORM\Column(name="sticker", type="string", nullable=true)
     * @var string
     */
    private $sticker;

    /**
     * @ORM\Column(name="photo", type="string", nullable=true)
     * @var string
     */
    private $photo;

    /**
     * @ORM\Column(name="animation", type="string", nullable=true)
     * @var string
     */
    private $animation;

    /**
     * @ORM\Column(name="audio", type="string", nullable=true)
     * @var string
     */
    private $audio;

    /**
     * @ORM\Column(name="video", type="string", nullable=true)
     * @var string
     */
    private $video;

    /**
     * @ORM\Column(name="voice", type="string", nullable=true)
     * @var string
     */
    private $voice;

    /**
     * @ORM\Column(name="document", type="string", nullable=true)
     * @var string
     */
    private $document;

    /**
     * @ORM\Column(name="pinned_message", type="integer", nullable=true)
     * @var TelegramMessage
     */
    private $pinnedMessage;

    /**
     * @ORM\Column(name="is_forwarded", type="boolean", nullable=true)
     * @var bool
     */
    private $isForwarded;

    /**
     * @ORM\Column(name="status", type="string", nullable=true)
     * @var string
     */
    private $status;
    /**
     * @var string|null
     */
    private $text;

    /**
     * TelegramMessage constructor.
     * @param int $messageId
     * @param TelegramUser $from
     * @param TelegramUser $chat
     * @param \DateTime $date
     * @param string $text
     */
    public function __construct(int $messageId, TelegramUser $from, TelegramUser $chat, \DateTime $date, ?string $text)
    {
        $this->messageId = $messageId;
        $this->from = $from;
        $this->chat = $chat;
        $this->date = $date->getTimestamp();
        $this->text = $text;
        $this->sticker = null;
        $this->photo = null;
        $this->animation = null;
        $this->audio = null;
        $this->video = null;
        $this->voice = null;
        $this->document = null;
        $this->pinnedMessage = null;
        $this->isForwarded = false;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

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
     * @return TelegramUser
     */
    public function getFrom(): TelegramUser
    {
        return $this->from;
    }

    /**
     * @param TelegramUser $from
     */
    public function setFrom(TelegramUser $from): void
    {
        $this->from = $from;
    }

    /**
     * @return TelegramUser
     */
    public function getChat(): TelegramUser
    {
        return $this->chat;
    }

    /**
     * @param TelegramUser $chat
     */
    public function setChat(TelegramUser $chat): void
    {
        $this->chat = $chat;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return (new \DateTime())->setTimestamp($this->date);
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getSticker(): string
    {
        return $this->sticker;
    }

    /**
     * @return string
     */
    public function getAnimation(): string
    {
        return $this->animation;
    }

    /**
     * @param string $animation
     */
    public function setAnimation(string $animation): void
    {
        $this->animation = $animation;
    }

    /**
     * @return string
     */
    public function getAudio(): string
    {
        return $this->audio;
    }

    /**
     * @param string $audio
     */
    public function setAudio(string $audio): void
    {
        $this->audio = $audio;
    }

    /**
     * @return string
     */
    public function getVideo(): string
    {
        return $this->video;
    }

    /**
     * @param string $video
     */
    public function setVideo(string $video): void
    {
        $this->video = $video;
    }

    /**
     * @return string
     */
    public function getVoice(): string
    {
        return $this->voice;
    }

    /**
     * @param string $voice
     */
    public function setVoice(string $voice): void
    {
        $this->voice = $voice;
    }

    /**
     * @return string
     */
    public function getDocument(): string
    {
        return $this->document;
    }

    /**
     * @param string $document
     */
    public function setDocument(string $document): void
    {
        $this->document = $document;
    }

    /**
     * @return TelegramMessage
     */
    public function getPinnedMessage(): TelegramMessage
    {
        return $this->pinnedMessage;
    }

    /**
     * @param TelegramMessage $pinnedMessage
     */
    public function setPinnedMessage(TelegramMessage $pinnedMessage): void
    {
        $this->pinnedMessage = $pinnedMessage;
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
     * @param string $sticker
     */
    public function setSticker(string $sticker): void
    {
        $this->sticker = $sticker;
    }

    /**
     * @return string
     */
    public function getPhoto(): string
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }
}