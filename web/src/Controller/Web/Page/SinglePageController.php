<?php

namespace App\Controller\Web\Page;

use App\Service\PageServiceInterface;
use App\Utility\DataEntityViewInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

class SinglePageController extends AbstractController
{

    public function __construct(
        protected PageServiceInterface $pageService,
        protected DataEntityViewInterface $dataEntityView,
        protected Environment $twig
    ) { }

    #[Route('/', name: 'app_homepage', priority: 2)]
    public function home(): Response
    {
        $page = $this->pageService->findBySlugAndMain();

        if (!$page) {
            throw $this->createNotFoundException();
        }

        $template_name = 'web/page/single-' . $page->getSlug() . '.html.twig';

        if(!$this->twig->getLoader()->exists($template_name)){
            $template_name = 'web/page/single.html.twig';
        }

        return $this->render($template_name, array_merge(
            $this->dataEntityView->getMeta($page),
            ['page' => $page]
        ));
    }

    #[Route('/{slug}', name: 'app_page_single')]
    public function index(string $slug = null): Response
    {
        $page = $this->pageService->findBySlugAndMain($slug);

        if($page && $page->isMain()){
            return $this->redirectToRoute('app_homepage');
        }

        if (!$page) {
            throw $this->createNotFoundException();
        }

        $template_name = 'web/page/single-' . $page->getSlug() . '.html.twig';

        if(!$this->twig->getLoader()->exists($template_name)){
            $template_name = 'web/page/single.html.twig';
        }

        return $this->render($template_name, array_merge(
            $this->dataEntityView->getMeta($page),
            ['page' => $page]
        ));
    }
}
