<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class LocaleListener
{
    private $defaultLocale;

    public function __construct(string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        if ($locale = $request->cookies->get('app_locale')) {
            $request->setLocale($locale);
        } else {
//            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

//        if ($locale = $request->getLocale()) {
//            $response->headers->setCookie(new Cookie('app_locale',
//                $locale,
//                strtotime('+1 year'),
//            '/',
//            null,
//            false,
//            false,
//            ));
//        }
    }
}