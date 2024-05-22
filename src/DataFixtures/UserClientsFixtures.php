<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserClient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserClientsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();

        $userClient = [
            [
                'email'       => 'user.client1@test.com',
                'firstname'   => 'user1',
                'lastname'    => 'client1',
            ],
            [
                'email'       => 'user.client2@test.com',
                'firstname'   => 'user2',
                'lastname'    => 'client2',
            ],
            [
                'email'       => 'user.client3@test.com',
                'firstname'   => 'user3',
                'lastname'    => 'client3',
            ],
            [
                'email'       => 'user.client4@test.com',
                'firstname'   => 'user4',
                'lastname'    => 'client4',
            ],
            [
                'email'       => 'user.client5@test.com',
                'firstname'   => 'user5',
                'lastname'    => 'client5',
            ],
            [
                'email'       => 'user.client6@test.com',
                'firstname'   => 'user6',
                'lastname'    => 'client6',
            ],
            [
                'email'       => 'user.client7@test.com',
                'firstname'   => 'user7',
                'lastname'    => 'client7',
            ],
            [
                'email'       => 'user.client8@test.com',
                'firstname'   => 'user8',
                'lastname'    => 'client8',
            ],
            [
                'email'       => 'user.client9@test.com',
                'firstname'   => 'user9',
                'lastname'    => 'client9',
            ],
            [
                'email'       => 'user.client10@test.com',
                'firstname'   => 'user10',
                'lastname'    => 'client10',
            ],
            [
                'email'       => 'user.client11@test.com',
                'firstname'   => 'user11',
                'lastname'    => 'client11',
            ],
            [
                'email'       => 'user.client12@test.com',
                'firstname'   => 'user12',
                'lastname'    => 'client12',
            ],
            [
                'email'       => 'user.client13@test.com',
                'firstname'   => 'user13',
                'lastname'    => 'client13',
            ],
            [
                'email'       => 'user.client13@test.com',
                'firstname'   => 'user13',
                'lastname'    => 'client13',
            ],
            [
                'email'       => 'user.client14@test.com',
                'firstname'   => 'user14',
                'lastname'    => 'client14',
            ],
            [
                'email'       => 'user.client15@test.com',
                'firstname'   => 'user15',
                'lastname'    => 'client15',
            ],
            [
                'email'       => 'user.client16@test.com',
                'firstname'   => 'user16',
                'lastname'    => 'client16',
            ],
            [
                'email'       => 'user.client17@test.com',
                'firstname'   => 'user17',
                'lastname'    => 'client17',
            ],
            [
                'email'       => 'user.client18@test.com',
                'firstname'   => 'user18',
                'lastname'    => 'client18',
            ],
        ];

        foreach ($userClient as $data) {
            $userClient = new UserClient();
            $userClient->setEmail($data['email']);
            $userClient->setFirstname($data['firstname']);
            $userClient->setLastname($data['lastname']);
            $userClient->setUser($users[array_rand($users)]);

            $manager->persist($userClient);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
