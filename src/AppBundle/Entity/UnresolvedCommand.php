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
    const COMMAND_SCHEDULE = '/schedule';
    const COMMAND_CANCEL = '/cancel';
    const COMMAND_SEEN = '/seen';

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
     * @var string
     */
    private $user;

    /**
     * @ORM\Column(name="command", type="string", length=255, nullable=false)
     * @var integer
     */
    private $command;

    /**
     * @ORM\Column(name="parameters", type="string", nullable=true)
     * @var string
     */
    private $parameters = null;

    /**
     * @ORM\Column(name="date", type="integer", nullable=true)
     * @var string
     */
    private $date;

    /**
     * UnresolvedCommand constructor.
     * @param int $id
     * @param TelegramManager $user
     * @param string $command
     * @param string $parameters
     * @param int $date
     */
    public function __construct(?int $id = null, TelegramManager $user, string $command, string $parameters, int $date)
    {
        $this->id = $id;
        $this->user = $user;
        $this->command = $command;
        $this->parameters = $parameters;
        $this->date = $date;
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
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getCommand(): int
    {
        return $this->command;
    }

    /**
     * @param int $command
     */
    public function setCommand(int $command): void
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
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

}