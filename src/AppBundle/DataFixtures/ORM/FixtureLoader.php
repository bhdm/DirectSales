<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Role;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Validator\Constraints\DateTime;

class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $role = new Role();
        $role->setTitle('ROLE_AGENT');
        $manager->persist($role);

        $role = new Role();
        $role->setTitle('ROLE_OPERATOR');
        $manager->persist($role);

        $role = new Role();
        $role->setTitle('ROLE_ADMIN');
        $manager->persist($role);
        $manager->flush();
        $manager->refresh($role);



        $user = new User();
        $user->setFirstName('admin');
        $user->setLastName('admin');
        $user->setSurName('admin');
        $user->setUsername('admin');
        $user->setCreated(new \DateTime());
        $user->setUpdated(new \DateTime());
        $user->setSalt(md5(time()));
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword('admin', $user->getSalt());
        $user->setPassword($password);
        $user->getUserRoles()->add($role);

        $manager->persist($user);

        $manager->flush();
    }
}