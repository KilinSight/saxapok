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
     * @var TelegramUser
     */
    private $user;

    /**
     * @ORM\Column(name="command", type="string", length=255, nullable=false)
     * @var string
     */
    private $command;

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
    public function __construct(?int $id = null, TelegramUser $user, string $command, string $parameters, int $date)
    {
        $this->id = $id;
        $this->user = $user;
        $this->command = $command;
        $this->parameters = $parameters;
        $this->date = $date;
    }

}