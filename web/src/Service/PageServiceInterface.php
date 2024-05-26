<?php

namespace App\Service;

use App\Entity\Page;

interface PageServiceInterface
{

    /**
     * @return Page[]
     */
    public function all(): array;

    /**
     * @return array|null
     */
    public function allGroupByLang(): ?array;

    /**
     * @param string $slug
     * @return Page|null
     */
    public function findBySlug(string $slug): ?Page;

    /**
     * @param int $id
     * @return Page|null
     */
    public function findById(int $id): ?Page;

    /**
     * @param string|null $slug
     * @return Page|null
     */
    public function findBySlugAndMain(string $slug = null): ?Page;
}