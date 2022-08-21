<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($iteration=1; $iteration < 4; $iteration++) {
            $imageOne = new Image();
            $imageOne->setName('ecran0' . $iteration . '.jpg');
            $product = $this->getReference('prod-' . 1);
            $imageOne->setProduct($product);
            
            $manager->persist($imageOne);
        }

        $faker = Faker\Factory::create('fr_FR');

        for ($img = 1; $img <= 100; $img++) {
            $image = new Image();
            $image->setName($faker->imageUrl(640, 480, 'image', true, null, true, 'jpg'));
            $product = $this->getReference('prod-' . rand(2, 10));
            $image->setProduct($product);

            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class
        ];
    }
}
