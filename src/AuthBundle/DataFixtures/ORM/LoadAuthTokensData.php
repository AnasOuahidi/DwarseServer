<?php
namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AuthBundle\Entity\User;
use AuthBundle\Entity\AuthToken;

class LoadAuthTokensData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function addAuthToken() {
        $authToken = new AuthToken();
        $authToken->setValue($this->generateToken(64));
        $authToken->setCreatedAt(new \DateTime('now'));
        return $authToken;
    }

    private function tokenToUser($manager, User $user, AuthToken $token) {
        $user->setAuthToken($token);
        $token->setUser($user);
        $manager->persist($token);
        $manager->merge($user);
    }


    public function load(ObjectManager $manager) {
        $this->tokenToUser($manager, $this->getReference('employe'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('employeur'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('commercant'), $this->addAuthToken());
        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }

    private function generateToken($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}