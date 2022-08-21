<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class ProductFixtures extends Fixture
{
    private $counter = 1;

    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $productOne = new Product();
        $this->createProduct(
            'Joli écran',
            $productOne,
            'Un très joli écran qui sert à afficher des choses',
            19999,
            10,
            'cat-' . 2,
            $manager
        );
        
        $faker = Faker\Factory::create('fr_FR');
        
        for ($prod = 1; $prod <= 15; $prod++) {
            $product = new Product();

            $this->createProduct(
                $faker->text(15),
                $product,
                $faker->text(),
                $faker->numberBetween(900, 150000),
                $faker->numberBetween(0, 10),
                'cat-' . rand(1, 8),
                $manager
            );
        }
        $manager->flush();
    }

    public function createProduct(
        string $name,
        object $product,
        string $description,
        int $price,
        int $stock,
        string $reference,
        ObjectManager $manager
    ) {
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setPrice($price);
        $product->setStock($stock);

        // On va chercher une référence de catégorie
        $category = $this->getReference($reference);
        $product->setCategory($category);

        $this->setReference('prod-' . $this->counter, $product);
        $this->counter++;

        $manager->persist($product);
    }
}
