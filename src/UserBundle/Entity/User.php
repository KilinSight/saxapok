<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    const USER_STATUS_ACTIVE = 'active';
    const USER_STATUS_DISABLED = 'disabled';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", unique=true)
     */
    private $username;

    /**
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @ORM\Column(name="salt", type="string")
     */
    private $salt;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="UserCustomization")
     * @ORM\JoinColumn(name="user_customization", referencedColumnName="id", nullable=true)
     */
    private $userCustomization;


    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     *
     * @var array|Role[]
     */
    private $roles;

    /**
     * User constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->roles = [];
        $this->salt = md5(uniqid(null, true));
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->status = 1;

    }


    public function getRoles()
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role;
        }
        return $roles;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function eraseCredentials()
    {
    }


    /**
     * Add role.
     *
     * @param Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $skip = false;
        if($this->roles && count($this->roles)){
            foreach ($this->roles as $userRole) {
                if($role->getRole() === $userRole->getRole()){
                    $skip = true;
                }
            }
        }

        if(!$skip){
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * Remove role.
     *
     * @param \UserBundle\Entity\Role $role
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRole(\UserBundle\Entity\Role $role)
    {
        return $this->roles->removeElement($role);
    }

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [self::USER_STATUS_ACTIVE, self::USER_STATUS_DISABLED];
    }

    /**
     * @return mixed
     */
    public function getUserCustomization()
    {
        return $this->userCustomization;
    }

    /**
     * @param mixed $userCustomization
     */
    public function setUserCustomization($userCustomization): void
    {
        $this->userCustomization = $userCustomization;
    }
}
