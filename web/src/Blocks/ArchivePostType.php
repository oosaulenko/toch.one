<?php

namespace App\Blocks;

use Adeliom\EasyGutenbergBundle\Blocks\AbstractBlockType;
use App\Form\Type\DefaultSettingsBlockType;
use App\Form\Type\LastPostsType;
use App\Service\PostServiceInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ArchivePostType extends AbstractBlockType
{
    public function __construct(
        protected PostServiceInterface $postService
    ) {}

    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('settings', DefaultSettingsBlockType::class, ['required' => false]);

        $builder->add('title', TextType::class, ['label' => 'Title']);
        $builder->add('text', TextEditorType::class, ['label' => 'Text']);
        $builder->add('posts', LastPostsType::class);
    }

    public static function getName(): string
    {
        return 'List posts';
    }

    public static function getPrefix(): string
    {
        return 'archive_post';
    }

    public static function getDescription(): string
    {
        return 'Show a list of posts';
    }

    public static function getIcon(): string
    {
        return ' fa fa-solid fa-bars';
    }

    public static function getCategory(): string
    {
        return 'common';
    }

    public static function getTemplate(): string
    {
        return 'blocks/archive_post.html.twig';
    }

    public static function configureAssets(): array
    {
        return [
            'js' => ['/build/block-archive_post.js'],
            'css' => ['/build/block-archive_post.css'],
        ];
    }

    public function render(array $data): array
    {
        $data['posts']['data'] = $this->postService->all();

        return array_merge($data, []);
    }
}
