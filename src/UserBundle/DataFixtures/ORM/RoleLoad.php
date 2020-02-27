<?php
namespace UserBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use UserBundle\Entity\Role;

class RoleLoad implements ORMFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $roleRepository = $manager->getRepository(Role::class);
        $role = $roleRepository->findOneBy(['role' => 'ROLE_USER']);
        if(!$role){
            $role = new Role();
            $role->setName("ROLE USER");
            $role->setRole("ROLE_USER");
            $manager->persist($role);
        }
        $role = $roleRepository->findOneBy(['role' => 'ROLE_ADMIN']);
        if(!$role){
            $role = new Role();
            $role->setName("ROLE ADMIN");
            $role->setRole("ROLE_ADMIN");
            $manager->persist($role);
        }
        $role = $roleRepository->findOneBy(['role' => 'ROLE_GUEST']);
        if(!$role){
            $role = new Role();
            $role->setName("ANONYMOUS");
            $role->setRole("ROLE_GUEST");
            $manager->persist($role);
        }
        $role = $roleRepository->findOneBy(['role' => 'ROLE_BOT']);
        if(!$role){
            $role = new Role();
            $role->setName("ROLE BOT");
            $role->setRole("ROLE_BOT");
            $manager->persist($role);
        }
        $role = $roleRepository->findOneBy(['role' => 'ROLE_CLIENT']);
        if(!$role){
            $role = new Role();
            $role->setName("ROLE CLIENT");
            $role->setRole("ROLE_CLIENT");
            $manager->persist($role);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return -999;
    }
}