<?php
namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AuthBundle\Entity\User;

class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;
    private $encoder;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
        $this->encoder = $this->container->get('security.password_encoder');
    }

    private function addUser($login, $email, $role) {
        $user = new User();
        $user->setLogin($login);
        $user->setPassword($login);
        $user->setEmail($email);
        $user->setPlainPassword($login);
        $encoded = $this->encoder->encodePassword($user, $login);
        $user->setPassword($encoded);
        $user->setRole($role);
        $user->setConfirmed(true);
        $user->setConfirmationToken(null);
        return $user;
    }

    public function load(ObjectManager $manager) {
        $employe = $this->addUser('employe', 'employe@dwarse.fr', 'ROLE_EMPLOYE');
        $employeur = $this->addUser('employeur', 'employeur@dwarse.fr', 'ROLE_EMPLOYEUR');
        $commercant = $this->addUser('commercant', 'commercant@dwarse.fr', 'ROLE_COMMERCANT');
        $manager->persist($employe);
        $manager->persist($employeur);
        $manager->persist($commercant);
        $manager->flush();
        $this->addReference('employe', $employe);
        $this->addReference('employeur', $employeur);
        $this->addReference('commercant', $commercant);
    }

    public function getOrder() {
        return 1;
    }
}