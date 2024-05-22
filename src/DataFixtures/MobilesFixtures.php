<?php

namespace App\DataFixtures;

use App\Entity\Mobile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MobilesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $mobiles = [
            [
                'name'        => 'Iphone 12',
                'brandName'   => 'Iphone',
                'description' => 'Apple Iphone 12, 128GB, 5G, 6.1 inches, 12MP, 2815mAh',
                'price'       => 1000,
                'color'       => 'Red',
                'stock'       => 125
            ],
            [
                'name'        => 'Samsung Galaxy S21',
                'brandName'   => 'Samsung',
                'description' => 'Samsung Galaxy S21, 128GB, 5G, 6.2 inches, 12MP, 4000mAh',
                'price'       => 900,
                'color'       => 'Black',
                'stock'       => 1490
            ],
            [
                'name'        => 'OnePlus 9',
                'brandName'   => 'OnePlus',
                'description' => 'OnePlus 9, 128GB, 5G, 6.55 inches, 48MP, 4500mAh',
                'price'       => 800,
                'color'       => 'Blue',
                'stock'       => 75
            ],
            [
                'name'        => 'Xiaomi Mi 11',
                'brandName'   => 'Xiaomi',
                'description' => 'Xiaomi Mi 11, 128GB, 5G, 6.81 inches, 108MP, 4600mAh',
                'price'       => 700,
                'color'       => 'White',
                'stock'       => 504
            ],
            [
                'name'        => 'Google Pixel 5',
                'brandName'   => 'Google',
                'description' => 'Google Pixel 5, 128GB, 5G, 6.0 inches, 12.2MP, 4080mAh',
                'price'       => 600,
                'color'       => 'Green',
                'stock'       => 257
            ],
            [
                'name'        => 'Sony Xperia 1 II',
                'brandName'   => 'Sony',
                'description' => 'Sony Xperia 1 II, 256GB, 5G, 6.5 inches, 12MP, 4000mAh',
                'price'       => 500,
                'color'       => 'Purple',
                'stock'       => 1000
            ],
            [
                'name'        => 'LG Velvet',
                'brandName'   => 'LG',
                'description' => 'LG Velvet, 128GB, 5G, 6.8 inches, 48MP, 4300mAh',
                'price'       => 400,
                'color'       => 'Yellow',
                'stock'       => 59
            ],
            [
                'name'        => 'Motorola Edge',
                'brandName'   => 'Motorola',
                'description' => 'Motorola Edge, 128GB, 5G, 6.7 inches, 64MP, 4500mAh',
                'price'       => 300,
                'color'       => 'Orange',
                'stock'       => 438
            ],
            [
                'name'        => 'Nokia 8.3',
                'brandName'   => 'Nokia',
                'description' => 'Nokia 8.3, 128GB, 5G, 6.81 inches, 64MP, 4500mAh',
                'price'       => 200,
                'color'       => 'Black',
                'stock'       => 942
            ],
            [
                'name'        => 'Asus Zenfone 7',
                'brandName'   => 'Asus',
                'description' => 'Asus Zenfone 7, 128GB, 5G, 6.67 inches, 64MP, 5000mAh',
                'price'       => 100,
                'color'       => 'White',
                'stock'       => 738
            ]
        ];

        foreach ($mobiles as $mobile) {
            $mobileEntity = (new Mobile())
                ->setName($mobile['name'])
                ->setBrandName($mobile['brandName'])
                ->setDescription($mobile['description'])
                ->setPrice($mobile['price'])
                ->setColor($mobile['color'])
                ->setStock($mobile['stock']);

            $manager->persist($mobileEntity);
        }

        $manager->flush();

    }
}
