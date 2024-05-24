<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = [
            [
                'email'       => 'user1@test.com',
                'password'    => 'password',
            ],
            [
                'email'       => 'user2@test.com',
                'password'    => 'password',
            ]
        ];

        foreach ($user as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
