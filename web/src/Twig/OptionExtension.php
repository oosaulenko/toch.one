<?php

namespace App\Twig;

use App\Service\MenuServiceInterface;
use App\Service\OptionServiceInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OptionExtension extends AbstractExtension
{
    public function __construct(
        protected OptionServiceInterface $optionService,
        protected Environment $twig
    ) { }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('option', [$this, 'option'], ['is_safe' => ['html']]),
        ];
    }

    public function option(string $name)
    {
        $option = $this->optionService->getSetting($name);
        if (!$option) return '';

        return $this->twig->render('web/component/option.html.twig', [
            'option' => $option->getValue()
        ]);
    }
}