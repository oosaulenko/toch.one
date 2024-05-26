<?php

namespace App\Service;

use App\Entity\Page;
use App\Repository\PageRepositoryInterface;
use Symfony\Component\Translation\LocaleSwitcher;

class PageService implements PageServiceInterface
{

    public function __construct(
        protected PageRepositoryInterface $repository,
        protected LocaleSwitcher $localeSwitcher
    ) { }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->repository->all();
    }

    public function allGroupByLang(): ?array
    {
        $pages = $this->repository->all();
        $pages_langs = [];

        foreach ($pages as $page) {
            $pages_langs[$page->getLocale()][] = $page;
        }

        return $pages_langs;
    }

    /**
     * @inheritDoc
     */
    public function findBySlug(string $slug): ?Page
    {
        $locale = $this->localeSwitcher->getLocale();

        return $this->repository->findBySlug($slug, $locale);
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id): ?Page
    {
        return $this->repository->findById($id);
    }

    public function findBySlugAndMain(string $slug = null): ?Page
    {
        if ($slug === null) {
            $locale = $this->localeSwitcher->getLocale();
            return $this->repository->findMainPage($locale);
        }

        return $this->findBySlug($slug);
    }
}