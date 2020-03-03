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
     * @param string $type
     */
    public function __construct(TelegramUser $user, \DateTime $date, string $chatId, ?string $type = null)
    {
        $this->user = $user;
        $this->date = $date;
        $this->chatId = $chatId;
        $this->type = $type;
        $this->message = null;
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
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $chatId;

       /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $command;

    /**
     * @var TelegramMessage
     */
    private $message;

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

    /**
     * @return TelegramMessage
     */
    public function getMessage(): TelegramMessage
    {
        return $this->message;
    }

    /**
     * @param TelegramMessage $message
     */
    public function setMessage(TelegramMessage $message): void
    {
        $this->message = $message;
    }


}