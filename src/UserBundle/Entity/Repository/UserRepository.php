<?php


namespace UserBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use UserBundle\Entity\UserCustomization;

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

    /**
     * @inheritDoc
     */
    public function loadUserByVerifyToken(string $token)
    {
        return $this->createQueryBuilder('u')
            ->join(UserCustomization::class, 'uC', Join::WITH, 'u.userCustomization=uC.id')
            ->where('uC.emailVerifyCode= :token')
            ->setParameter('token', $token)->getQuery()->getOneOrNullResult();
    }
}