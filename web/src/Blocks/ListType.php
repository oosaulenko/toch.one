<?php

namespace App\Blocks;

use Adeliom\EasyGutenbergBundle\Blocks\AbstractBlockType;
use App\Form\Type\BasicCollectionType;
use App\Form\Type\DefaultSettingsBlockType;
use App\Service\PostServiceInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Oosaulenko\MediaBundle\Form\Type\MediaChoiceType;
use Oosaulenko\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ListType extends AbstractBlockType
{
    public function __construct(protected PostServiceInterface $postService) {}

    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('settings', DefaultSettingsBlockType::class, ['required' => false]);

        $builder->add('title', TextType::class, ['label' => 'Title']);
        $builder->add('text', TextEditorType::class, ['label' => 'Text']);
        $builder->add('list', CollectionType::class, [
            'label' => 'Items',
            'entry_type' => BasicCollectionType::class,
            'attr' => [
                'class' => 'field-collection field-collection-sortable',
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ]);
    }

    public static function getName(): string
    {
        return 'List';
    }

    public static function getDescription(): string
    {
        return 'List block';
    }

    public static function getIcon(): string
    {
        return ' fa fa-light fa-list';
    }

    public static function getCategory(): string
    {
        return 'common';
    }

    public static function getTemplate(): string
    {
        return 'blocks/list.html.twig';
    }

    public static function configureAssets(): array
    {
        return [
            'js' => [
                '/build/block-list.js'
            ],
            'css' => [
                '/build/block-list.css'
            ],
        ];
    }

    public static function configureAdminAssets(): array
    {
        return [
            'js' => ['/bundles/oosaulenkomedia/js/media-bundle.js'],
            'css' => [
                '/bundles/oosaulenkomedia/css/manager.css',
                '/build/block-list.css'
            ],
        ];
    }

    public function render(array $data): array
    {
        return array_merge($data, []);
    }
}
