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
     * @ORM\Column(name="status", type="string", nullable=true)
     * @var string
     */
    private $status;

    /**
     * TelegramMessage constructor.
     * @param int|null $id
     * @param int $messageId
     * @param TelegramUser $from
     * @param TelegramUser $chat
     * @param \DateTime $date
     * @param string $text
     */
    public function __construct(?int $id, int $messageId, TelegramUser $from, TelegramUser $chat, \DateTime $date, string $text)
    {
        $this->id = $id;
        $this->messageId = $messageId;
        $this->from = $from;
        $this->chat = $chat;
        $this->date = $date;
        $this->text = $text;
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
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @ORM\Column(name="text", type="string", nullable=true)
     * @var string
     */
    private $text = '';

    public static function getAllowedCommands(): array
    {
        return [
            self::COMMAND_REPLY,
            self::COMMAND_SCHEDULE,
            self::COMMAND_SEEN,
            self::COMMAND_CANCEL
        ];
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
}