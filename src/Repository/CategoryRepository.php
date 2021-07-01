<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Category;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;
use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\Serializer\Normalizer;
use Symfony\Component\PropertyInfo\Extractor;
use Symfony\Component\Serializer;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        $content = file_get_contents('..\data\categories.json');
        $categories = json_decode($content, true);
        $catsList = array();

        foreach($categories as $result)
        {
            $category = new Category();
            $category->setId($result['id']);
            $category->setName($result['name']);

            $catsList[] = $category;
        }

        return $catsList;
    }
}