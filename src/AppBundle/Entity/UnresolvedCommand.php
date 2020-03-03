<?php
namespace AppBundle\Entity;

use AppBundle\Manager\TelegramManager;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="unresolved_command")
 */
class UnresolvedCommand
{

    const COMMAND_REPLY = '/reply';
    const COMMAND_MENU = '/menu';
    const COMMAND_NOTIFY = '/notify';
    const COMMAND_SCHEDULE = '/schedule';
    const COMMAND_SEEN = '/seen';
    const COMMAND_START = '/start';
    const COMMAND_CANCEL = '/cancel';
    const COMMAND_STOP = '/stop';
    const COMMAND_DEBUG = '/debug';

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", length=11, unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TelegramUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var TelegramUser
     */
    private $user;

    /**
     * @ORM\Column(name="command", type="string", length=255, nullable=false)
     * @var string
     */
    private $command;

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
     * @return string
     */
    public function getParameters(): string
    {
        return $this->parameters;
    }

    /**
     * @param string $parameters
     */
    public function setParameters(string $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate(int $date): void
    {
        $this->date = $date;
    }

    /**
     * @ORM\Column(name="parameters", type="string", nullable=true)
     * @var string
     */
    private $parameters = null;

    /**
     * @ORM\Column(name="date", type="integer", nullable=true)
     * @var integer
     */
    private $date;

    /**
     * UnresolvedCommand constructor.
     * @param int $id
     * @param TelegramUser $user
     * @param string $command
     * @param string $parameters
     * @param int $date
     */
    public function __construct(TelegramUser $user, string $command, string $parameters, int $date)
    {
        $this->user = $user;
        $this->command = $command;
        $this->parameters = $parameters;
        $this->date = $date;
    }

    public static function getAllowedCommands(): array
    {
        return [
            self::COMMAND_REPLY,
            self::COMMAND_SCHEDULE,
            self::COMMAND_SEEN,
            self::COMMAND_DEBUG,
            self::COMMAND_STOP,
            self::COMMAND_CANCEL
        ];
    }

}