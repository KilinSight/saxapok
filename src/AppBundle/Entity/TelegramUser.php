<?php
namespace AppBundle\Entity;

use AppBundle\Manager\TelegramManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="telegram_user")
 */
class TelegramUser
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", length=11, unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(name="user_id", type="integer", length=11, nullable=false)
     * @var string
     */
    private $userId;

    /**
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     * @var string
     */
    private $firstname = null;

    /**
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     * @var string
     */
    private $lastname = null;

    /**
     * @ORM\Column(name="is_bot", type="boolean", nullable=false)
     * @var string
     */
    private $isBot = false;

    /**
     * @ORM\OneToMany(targetEntity="TelegramMessage", mappedBy="from")
     *
     * @var ArrayCollection|TelegramMessage[]
     */
    private $fromMessages;

    /**
     * @ORM\OneToMany(targetEntity="TelegramMessage", mappedBy="chat")
     *
     * @var ArrayCollection|TelegramMessage[]
     */
    private $toMessages;

    /**
     * TelegramUser constructor.
     * @param int|null $id
     * @param string $userId
     * @param string $username
     * @param string $firstname
     * @param string $lastname
     * @param bool|null $isBot
     */
    public function __construct(?int $id, string $userId, string $username, ?string $firstname = null, ?string $lastname = null, ?bool $isBot = false)
    {
        $this->id = $id;
        $this->username = $username;
        $this->userId = $userId;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->isBot = $isBot;
        $this->fromMessages = new ArrayCollection();
        $this->toMessages = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return bool|null
     */
    public function getIsBot(): ?bool
    {
        return $this->isBot;
    }

    /**
     * @param bool|null $isBot
     */
    public function setIsBot(?bool $isBot): void
    {
        $this->isBot = $isBot;
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

    public static function getBotUser():TelegramUser
    {
        return new TelegramUser(1, 914200924, 'SaxapokBot', 'SaharokBot', '', true);
    }

    public static function getAdminUser():TelegramUser
    {
        return new TelegramUser(2, TelegramManager::CHAT_ID_ME, 'kilinsight', 'Илья', 'Украинский', false);
    }

    /**
     * @return TelegramMessage[]|ArrayCollection
     */
    public function getFromMessages()
    {
        return $this->fromMessages;
    }

    /**
     * @param TelegramMessage[]|ArrayCollection $fromMessages
     */
    public function setFromMessages($fromMessages): void
    {
        $this->fromMessages = $fromMessages;
    }

    /**
     * @return TelegramMessage[]|ArrayCollection
     */
    public function getToMessages()
    {
        return $this->toMessages;
    }

    /**
     * @param TelegramMessage[]|ArrayCollection $toMessages
     */
    public function setToMessages($toMessages): void
    {
        $this->toMessages = $toMessages;
    }

}