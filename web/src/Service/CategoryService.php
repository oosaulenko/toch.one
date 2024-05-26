<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepositoryInterface;
use Symfony\Component\Translation\LocaleSwitcher;

class CategoryService implements CategoryServiceInterface
{

    public function __construct(
        protected CategoryRepositoryInterface $repository,
        protected LocaleSwitcher $localeSwitcher
    ) { }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->repository->all();
    }

    /**
     * @inheritDoc
     */
    public function findBySlug(string $slug): ?Category
    {
        $locale = $this->localeSwitcher->getLocale();

        return $this->repository->findBySlug($slug, $locale);
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id): mixed
    {
        return $this->repository->findById($id);
    }
}