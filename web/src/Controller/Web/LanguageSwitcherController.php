<?php

declare(strict_types=1);

namespace App\Controller\Web;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LanguageSwitcherController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) { }

    #[Route('/language-switcher', name: 'language_switcher')]
    public function index(Request $request): Response
    {
        $language = $request->request->get('language', 'en');
        $relative = json_decode($request->request->get('relative'), true);

        $repository = $this->entityManager->getRepository($request->request->get('entity_name', 'App\Entity\Page'));

        $lang_code = ($language == 'en') ? '/' : '/'.$language.'/';
        $url = $lang_code;

        foreach ($relative as $locale) {
            if ($locale['locale'] == $language && !empty($locale['entity'])) {
                $entity = $repository->findById($locale['entity']);

                if(!$entity || (method_exists($entity, 'isMain') && $entity->isMain())) break;

                $section = (method_exists($entity, '_getSection')) ? $entity->_getSection().'/' : '';

                $url = $lang_code.$section.$entity->getSlug();
                break;
            }
        }

        setcookie('app_locale', $language, strtotime('+1 year'), '/');

        return $this->redirect($url);
    }
}
