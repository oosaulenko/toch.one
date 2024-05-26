<?php

namespace App\Service;

use App\Entity\Menu;
use App\Repository\MenuRepositoryInterface;
use Symfony\Component\Translation\LocaleSwitcher;

class MenuService implements MenuServiceInterface
{

    public function __construct(
        protected MenuRepositoryInterface $repository,
        protected LocaleSwitcher $localeSwitcher
    ) { }

    public function getMenu(string $location): ?Menu
    {
        $locale = $this->localeSwitcher->getLocale();
        return $this->repository->getMenu($location, $locale);
    }
}