<?php

namespace App\Controller\Admin;

use Adeliom\EasyGutenbergBundle\Admin\Field\GutenbergField;
use App\Admin\Field\DataField;
use App\Admin\Field\StatusField;
use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Oosaulenko\MediaBundle\Form\Type\MediaChoiceType;

class PostCrudController extends BaseCrudController
{

    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = parent::configureFields($pageName);

        $categoryField = AssociationField::new('category')
            ->setColumns(12)
            ->setQueryBuilder(function ($repository) {
                return $repository
                    ->where('entity.locale = :locale')
                    ->setParameter('locale', $this->localeSwitcher->getLocale());
            })
        ;

        if ($pageName === Crud::PAGE_INDEX) {
            $categoryField->formatValue(function ($value, Post $entity) {
                return implode(',', $entity->getCategory()->map(function ($category) {
                    return $category->getTitle();
                })->toArray());
            });
        }

        $fields[0] = FormField::addTab('General');
        $fields[6] = GutenbergField::new('content')->onlyOnForms()->setLabel(false);

        $fields[31] = FormField::addTab('Settings')->setIcon('fa fa-cog');
        $fields[38] = FormField::addColumn('col-lg-4 col-xl-4');
        $fields[39] = FormField::addFieldset();
        $fields[41] = $categoryField;
        $fields[42] = StatusField::new('status');
        $fields[43] = Field::new('feature_image')->setFormType(MediaChoiceType::class)->onlyOnForms();

        $fields[49] = FormField::addFieldset();

        $fields[60] = FormField::addColumn('col-lg-8 col-xl-8');
        $fields[61] = FormField::addFieldset();
        $fields[62] = DataField::new('data')->setLabel(false)->hideOnIndex()->setColumns(12);

        return $this->sortByKeyFields($fields);
    }
}
