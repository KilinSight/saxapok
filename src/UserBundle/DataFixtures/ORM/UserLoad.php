<?php
namespace UserBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use UserBundle\Entity\Role;
use UserBundle\Entity\User;
use UserBundle\Entity\UserCustomization;

class UserLoad implements ORMFixtureInterface
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $roleRepository = $manager->getRepository(Role::class);
        /** @var Role $role */
        $role = $roleRepository->findOneBy(['role' => 'ROLE_ADMIN']);
        if(!$role){
            return;
        }
        $user = $manager->getRepository(User::class)->loadUserByUsername('kilinsight');

        if(!$user){
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'passw0rd');
            $user->setUsername('kilinsight');
            $user->setPassword($password);
            $user->addRole($role);
            $user->setEmail('kilinsight@mail.ru');

            $manager->persist($user);

            $userCustomization = new UserCustomization();
            $userCustomization->setFirstname('Ilya');
            $userCustomization->setUser($user);
            $userCustomization->setLastname('Ukrainskiy');
            $userCustomization->setBirthday((new \DateTime())->setDate(1994, 7, 31));
            $user->setUserCustomization($userCustomization);
            $manager->persist($userCustomization);
            $manager->flush();
        }

        $role = $roleRepository->findOneBy(['role' => 'ROLE_BOT']);
        if(!$role){
            return;
        }
        $user = $manager->getRepository(User::class)->loadUserByUsername('saxapok_bot');

        if(!$user){
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'passw0rdbot');
            $user->setUsername('saxapok_bot');
            $user->setPassword($password);
            $user->addRole($role);
            $user->setEmail('support@my-fathers-voice.com');

            $manager->persist($user);

            $userCustomization = new UserCustomization();
            $userCustomization->setFirstname('Saxapok');
            $userCustomization->setUser($user);
            $userCustomization->setLastname('Bot');
            $userCustomization->setBirthday((new \DateTime())->setDate(2020, 2, 01));
            $user->setUserCustomization($userCustomization);
            $manager->persist($userCustomization);
            $manager->flush();
        }

    }

    public function getOrder()
    {
        return -998;
    }
}