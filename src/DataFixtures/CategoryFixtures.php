<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    private $counter = 1;

    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory(name: 'Informatique', parent: null, categoryOrder: 1, manager: $manager);
        
        $this->createCategory('Ecrans', $parent, 2, $manager);
        $this->createCategory('Clavier', $parent, 3, $manager);
        $this->createCategory('Ordinateurs portables', $parent, 4, $manager);
        
        $parent = $this->createCategory(name: 'Mode', parent: null, categoryOrder: 5, manager: $manager);
        
        $this->createCategory('Femme', $parent, 6, $manager);
        $this->createCategory('Homme', $parent, 7, $manager);
        $this->createCategory('Enfant', $parent, 8, $manager);

        $manager->flush();
    }

    public function createCategory(string $name, Category $parent = null, string $categoryOrder, ObjectManager $manager)
    {
        $category = new Category();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $category->setCategoryOrder($categoryOrder);

        $manager->persist($category);

        $this->addReference('cat-' . $this->counter, $category);
        $this->counter++;

        return $category;
    }
}
