<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function all(): array
    {
        return self::findAll();
    }

    public function findBySlug($slug): mixed
    {
        return self::findOneBy(['slug' => $slug]);
    }

    public function findById($id): mixed
    {
        return self::findOneBy(['id' => $id]);
    }
}
