<?php

namespace App\EventListener;

use App\Utility\LanguagesInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class LocaleRedirectListener
{
    private $defaultLocale = 'en';

    public function __construct(
        protected RouterInterface $router,
        protected LanguagesInterface $languages
    ) { }

    private function getLocale(string $url): string
    {
        if (preg_match('/^\/(\w{2})(\/|$)/', $url, $matches)) {
            if (in_array($matches[1], $this->languages->getLanguages(true))) {
                return $matches[1];
            }
        }

        return $this->defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();
        $app_locale = $request->cookies->get('app_locale', $this->defaultLocale);

        $page_locale = $this->getLocale($pathInfo);

        if (
            strpos($pathInfo, '/admin') === 0 ||
            strpos($pathInfo, '/bundles') === 0 ||
            strpos($pathInfo, '/bundle') === 0 ||
            strpos($pathInfo, '_profiler') === 0 ||
            $request->isXmlHttpRequest()
        ) {
            return;
        }

        if($app_locale !== $page_locale) {
            $pathInfo = str_replace('/'. $page_locale . '/', '/', $pathInfo);

            if($app_locale == $this->defaultLocale) {
                $newUrl = $pathInfo;
            } else {
                $newUrl = '/' . $app_locale . $pathInfo;
            }
            $event->setResponse(new RedirectResponse($newUrl));
        }



//        if(!preg_match(sprintf('/^\/%s\//', preg_quote($locale)), $pathInfo)) {
//            if($locale == $this->defaultLocale) {
//                $newUrl =
//            }
//            $newUrl = '/' . $locale . $pathInfo;
//            $event->setResponse(new RedirectResponse($newUrl));
//        }


        // Проверяем, содержит ли URL локаль
//        if (!preg_match(sprintf('/^\/%s\//', preg_quote($locale)), $pathInfo) && $locale !== $this->defaultLocale) {
//            $newUrl = '/' . $locale . $pathInfo;
//            $event->setResponse(new RedirectResponse($newUrl));
//        }
    }
}