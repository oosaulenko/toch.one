<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Page>
 */
class PageRepository extends ServiceEntityRepository implements PageRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Page::class);
    }

    public function all(): array
    {
        return self::findAll();
    }

    public function findBySlug($slug, string $locale = 'en'): ?Page
    {
        return self::findOneBy([
            'slug' => $slug,
            'locale' => $locale
        ]);
    }

    public function findById($id): ?Page
    {
        return self::findOneBy(['id' => $id]);
    }

    public function findMainPage(string $locale = 'en'): ?Page
    {
        return self::findOneBy([
            'is_main' => true,
            'locale' => $locale
        ]);
    }

    public function findByLocale(string $locale, array $params = []): ?array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.locale = :locale')
            ->setParameter('locale', $locale);

        return $qb->getQuery()->execute();
    }
}
