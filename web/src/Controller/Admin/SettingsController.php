<?php

namespace App\Controller\Admin;

use App\DTO\SettingsDTO;
use App\Entity\LoolyMedia\Media;
use App\Form\SettingsType;
use App\Service\OptionServiceInterface;
use Oosaulenko\MediaBundle\Form\UploadFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    public function __construct(
        protected OptionServiceInterface $optionService
    ) { }

    #[Route('/admin/settings/', name: 'admin_settings')]
    public function __invoke(Request $request, FormFactoryInterface $formFactory): Response
    {
        $settingsDto = new SettingsDTO();
        $settingsDto->siteName = $this->optionService->getSetting('siteName') ? $this->optionService->getSetting('siteName')->getValue() : 'Skeleton';
        $settingsDto->siteLogo = $this->optionService->getSetting('siteLogo') ? $this->optionService->getSetting('siteLogo')->getValue() : '';
        $settingsDto->siteLangs = $this->optionService->getSetting('siteLangs') ? json_decode($this->optionService->getSetting('siteLangs')->getValue(), true) : [];
        $settingsDto->siteDefaultLang = $this->optionService->getSetting('siteDefaultLang') ? $this->optionService->getSetting('siteDefaultLang')->getValue() : 'en';

        $form = $formFactory->create(SettingsType::class, $settingsDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->optionService->setSetting('siteName', $form->get('siteName')->getData());
            $this->optionService->setSetting('siteLogo', $form->get('siteLogo')->getData());
            $this->optionService->setSetting('siteLangs', json_encode($form->get('siteLangs')->getData()));
            $this->optionService->setSetting('siteDefaultLang', $form->get('siteDefaultLang')->getData());
            $this->optionService->save();
            $this->addFlash('success', 'Settings updated successfully');

//            return $this->redirectToRoute('admin_settings');
        }

        return $this->render('admin/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}