<?php

namespace App\Service;

use App\Entity\Option;
use App\Repository\OptionRepositoryInterface;

class OptionService implements OptionServiceInterface
{
    public function __construct(
        protected OptionRepositoryInterface $repository
    ) { }

    public function getSettings(): ?array
    {
        return $this->repository->getSettings();
    }

    public function getSetting(string $name): ?Option
    {
        return $this->repository->getSetting($name);
    }

    public function setSetting(string $name, string $value = null, bool $flush = false): void
    {
        $this->repository->setSetting($name, $value, $flush);
    }

    public function save(): void
    {
        $this->repository->save();
    }
}