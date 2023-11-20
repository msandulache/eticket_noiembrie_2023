<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager) {

        $categories = [
            [
                'name' => 'Popular'
            ],
            [
                'name' => 'Romanian Night'
            ],
            [
                'name' => 'Films français'
            ],
            [
                'name' => 'Now playing'
            ],
        ];

        foreach ($categories as $category) {
            $manager->persist($this->getCategory($category));
        }
        $manager->flush();
    }

    private function getCategory($category) {

        $categoryObj = new Category();
        $categoryObj->setName($category['name']);
        $categoryObj->setStatus('1');

        return $categoryObj;
    }
}
