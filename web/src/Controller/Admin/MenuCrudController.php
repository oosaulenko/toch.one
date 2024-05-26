<?php

namespace App\Controller\Admin;

use App\Admin\Field\TitleField;
use App\Entity\Menu;
use App\Form\Type\MenuItemType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Symfony\Component\Translation\LocaleSwitcher;

class MenuCrudController extends BaseCrudController
{
    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // General
            FormField::addTab('General'),
            TitleField::new('title'),
            CollectionField::new('items')
                ->setEntryType(MenuItemType::class)
                ->hideOnIndex()
                ->addCssClass('field-collection-sortable')
                ->setColumns(12),

            // Settings
            FormField::addTab('Settings')->setIcon('fa fa-cog'),
            ChoiceField::new('location')->setChoices([
                'Header' => 'header',
                'Footer' => 'footer',
            ]),
        ];
    }

    public function createEntity(string $entityFqcn): Menu
    {
        $menu = new Menu();
        $menu->setCreatedAtDefault();
        $menu->setUpdatedAtDefault();
        $menu->setUser($this->getUser());
        $menu->setLocale($this->localeSwitcher->getLocale());

        return $menu;
    }
}
