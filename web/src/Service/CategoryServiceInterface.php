<?php

namespace App\Service;

use App\Entity\Category;

interface CategoryServiceInterface
{

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param string $slug
     * @return Category|null
     */
    public function findBySlug(string $slug): ?Category;

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

}