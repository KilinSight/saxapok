<?php


namespace UserBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')->where('u.username= :username OR u.email= :email')
            ->setParameter('username', $username)->setParameter('email', $username)->getQuery()->getOneOrNullResult();
    }
}