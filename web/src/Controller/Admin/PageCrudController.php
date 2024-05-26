<?php

namespace App\Controller\Admin;

use Adeliom\EasyGutenbergBundle\Admin\Field\GutenbergField;
use App\Admin\Field\DataField;
use App\Admin\Field\StatusField;
use App\Entity\Page;
use App\Service\PageServiceInterface;
use App\Utility\LanguagesInterface;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Symfony\Component\Translation\LocaleSwitcher;

class PageCrudController extends BaseCrudController
{
    public function __construct(
        protected PageServiceInterface $pageService,
        protected LanguagesInterface   $languages,
        protected LocaleSwitcher       $localeSwitcher
    )
    {
        parent::__construct($localeSwitcher, $languages);
    }

    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = parent::configureFields($pageName);

        $fields[0] = FormField::addTab('General');
        $fields[6] = GutenbergField::new('content')->onlyOnForms()->setLabel(false);

        $fields[31] = FormField::addTab('Settings')->setIcon('fa fa-cog');
        $fields[38] = FormField::addColumn('col-lg-4 col-xl-4');
        $fields[39] = FormField::addFieldset();
        $fields[42] = BooleanField::new('main')
            ->setLabel('Is main page?')
            ->setHelp('This page will be the main page of the website.')
            ->renderAsSwitch(false);
        $fields[43] = StatusField::new('status');

        $fields[49] = FormField::addFieldset();

        $fields[60] = FormField::addColumn('col-lg-8 col-xl-8');
        $fields[61] = FormField::addFieldset();
        $fields[62] = DataField::new('data');

        return $this->sortByKeyFields($fields);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->updateMainPage($entityManager, $entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->updateMainPage($entityManager, $entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    private function updateMainPage(EntityManagerInterface $entityManager, Page $entityInstance): void
    {
        if(!$entityInstance->isMain()) return;

        $mainPage = $this->pageService->findBySlugAndMain();
        if($mainPage && $mainPage->getId() !== $entityInstance->getId()) {
            $mainPage->setMain(false);
            $entityManager->persist($mainPage);
        }
    }
}
