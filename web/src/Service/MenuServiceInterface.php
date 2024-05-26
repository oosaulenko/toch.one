<?php

namespace App\Service;

use App\Entity\Menu;

interface MenuServiceInterface
{

    /**
     * @param string $location
     * @return Menu|null
     */
    public function getMenu(string $location): ?Menu;
}