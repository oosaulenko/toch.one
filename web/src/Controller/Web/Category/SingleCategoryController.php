<?php

namespace App\Controller\Web\Category;

use App\Service\CategoryServiceInterface;
use App\Utility\DataEntityViewInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

class SingleCategoryController extends AbstractController
{

    public function __construct(
        protected CategoryServiceInterface $categoryService,
        protected DataEntityViewInterface $dataEntityView,
        protected Environment $twig
    ) { }

    #[Route('/category/{slug}', name: 'app_category_single')]
    public function index(string $slug = null): Response
    {
        $category = $this->categoryService->findBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException();
        }

        $template_name = 'web/category/single-' . $category->getSlug() . '.html.twig';

        if(!$this->twig->getLoader()->exists($template_name)){
            $template_name = 'web/category/single.html.twig';
        }

        return $this->render($template_name, array_merge(
            $this->dataEntityView->getMeta($category),
            [
                'page' => $category,
                'posts' => $category->getPosts()->toArray()
            ]
        ));
    }
}
