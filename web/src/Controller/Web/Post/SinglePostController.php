<?php

namespace App\Controller\Web\Post;

use App\Service\PostServiceInterface;
use App\Utility\DataEntityViewInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SinglePostController extends AbstractController
{

    public function __construct(
        protected PostServiceInterface $postService,
        protected DataEntityViewInterface $dataEntityView,
    ) { }

    #[Route('/post/{slug}', name: 'app_post_single')]
    public function index(string $slug): Response
    {
        $post = $this->postService->findBySlug($slug);

        if (!$post) {
            throw $this->createNotFoundException();
        }

        return $this->render('web/post/single.html.twig', array_merge(
            $this->dataEntityView->getMeta($post),
            ['page' => $post],
        ));
    }
}
