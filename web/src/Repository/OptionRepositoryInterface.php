<?php

namespace App\Repository;

use App\Entity\Option;

interface OptionRepositoryInterface
{

    /**
     * @return Option[]|null
     */
    public function getSettings(): ?array;

    public function getSetting(string $name): ?Option;

    public function setSetting(string $name, string $value = null, bool $flush = false): void;

    public function save(): void;
}