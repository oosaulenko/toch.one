<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function all(): array
    {
        return self::findAll();
    }

    public function findBySlug(string $slug, string $locale = 'en'): ?Category
    {
        return self::findOneBy([
            'slug' => $slug,
            'locale' => $locale
        ]);
    }

    public function findById(int $id): mixed
    {
        return self::findOneBy(['id' => $id]);
    }
}
