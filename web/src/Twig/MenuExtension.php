<?php

namespace App\Twig;

use App\Service\MenuServiceInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function __construct(
        protected MenuServiceInterface $menuService,
        protected Environment $twig
    ) { }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('menu', [$this, 'menu'], ['is_safe' => ['html']]),
        ];
    }

    public function menu(string $location)
    {
        $menu = $this->menuService->getMenu($location);
        if (!$menu) return '';

        return $this->twig->render('web/component/menu.html.twig', [
            'menu' => $menu->getItems()
        ]);
    }
}