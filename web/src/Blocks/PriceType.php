<?php

namespace App\Blocks;

use Adeliom\EasyGutenbergBundle\Blocks\AbstractBlockType;
use App\Form\Type\BasicCollectionType;
use App\Form\Type\DefaultSettingsBlockType;
use App\Form\Type\PriceCollectionType;
use App\Service\PostServiceInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Oosaulenko\MediaBundle\Form\Type\MediaChoiceType;
use Oosaulenko\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PriceType extends AbstractBlockType
{
    public function __construct(protected PostServiceInterface $postService) {}

    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('settings', DefaultSettingsBlockType::class, ['required' => false]);

        $builder->add('title', TextType::class, ['label' => 'Title']);
        $builder->add('list', CollectionType::class, [
            'label' => 'Items',
            'entry_type' => PriceCollectionType::class,
            'attr' => [
                'class' => 'field-collection field-collection-sortable',
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ]);
    }

    public static function getName(): string
    {
        return 'Price';
    }

    public static function getDescription(): string
    {
        return 'Price block';
    }

    public static function getIcon(): string
    {
        return ' fa fa-light fa-money-bill-wave';
    }

    public static function getCategory(): string
    {
        return 'common';
    }

    public static function getTemplate(): string
    {
        return 'blocks/price.html.twig';
    }

    public static function configureAssets(): array
    {
        return [
            'js' => [
                '/build/block-price.js'
            ],
            'css' => [
                '/build/block-price.css'
            ],
        ];
    }

    public static function configureAdminAssets(): array
    {
        return [
            'js' => ['/bundles/oosaulenkomedia/js/media-bundle.js'],
            'css' => [
                '/bundles/oosaulenkomedia/css/manager.css',
                '/build/block-price.css'
            ],
        ];
    }

    public function render(array $data): array
    {
        return array_merge($data, []);
    }
}
