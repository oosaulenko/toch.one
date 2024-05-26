<?php

namespace App\Twig;

use App\Service\MenuServiceInterface;
use App\Service\OptionServiceInterface;
use App\Utility\LanguagesInterface;
use Oosaulenko\MediaBundle\Service\MediaServiceInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LanguageSwitcherExtension extends AbstractExtension
{
    public function __construct(
        protected LanguagesInterface $languages,
        protected Environment $twig
    ) { }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('language_switcher', [$this, 'view'], ['is_safe' => ['html']]),
        ];
    }

    public function view($entity): string
    {
        if(!$entity->getRelativeLocales()) return '';

        $supportedLangs = $this->languages->getSupportLangs();
        $langs = [];

        foreach ($entity->getRelativeLocales() as $relativeLocale) {
            $locale = $relativeLocale['locale'];
            $_entity = $relativeLocale['entity'];

            if (!isset($supportedLangs[$locale]) || !$_entity) continue;

            $langs[$locale] = $supportedLangs[$locale];
        }

        return $this->twig->render('web/component/language_switcher.html.twig', [
            'langs' => $langs,
            'page' => $entity,
            'entity_name' => get_class($entity),
            'relativeLocales' => json_encode($entity->getRelativeLocales()),
        ]);
    }
}