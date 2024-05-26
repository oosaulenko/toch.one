<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class CategoryCrudController extends BaseCrudController
{

    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = parent::configureFields($pageName);

        $fields[2] = FormField::addColumn('col-lg-8 col-xl-8');
        $fields[15] = FormField::addRow();
        $fields[41] = TextareaField::new('description')
            ->setMaxLength(100)
            ->setHelp('This description will be displayed on the category page.')
            ->setColumns(12);

        return $this->sortByKeyFields($fields);
    }
}
