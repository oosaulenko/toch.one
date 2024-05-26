<?php

namespace App\Controller\EasyGutenberg;

use Adeliom\EasyGutenbergBundle\Blocks\Block;
use Adeliom\EasyGutenbergBundle\Blocks\BlockTypeRegistry;
use Adeliom\EasyGutenbergBundle\Blocks\ContentRenderer;
use Adeliom\EasyGutenbergBundle\Requests\BlockRenderRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/bundles/easy-gutenberg/fetch-blocks', name: 'easy_gutenberg.fetch_blocks')]
    public function fetchBlocks(BlockTypeRegistry $registry): JsonResponse
    {
        $blocks = [];
        foreach ($registry->blockTypes() as $blockType) {
            $blocks[$blockType::getKey()] = array_filter([
                'title' => $blockType::getName(),
                'description' => $blockType::getDescription() ?: null,
                'category' => $blockType::getCategory(),
                'icon' => $blockType::getIcon() ?: null,
                'attributes' => $blockType::getAttributes() ?: null,
                'styles' => $blockType::getStyles() ?: null,
                'supports' => $blockType::getSupports() ?: null,
                'variations' => $blockType::getVariations() ?: null,
            ]);
        }

        return $this->json($blocks);
    }

    #[Route('/bundles/easy-gutenberg/block-renderer', name: 'easy_gutenberg.block_renderer')]
    public function show(BlockRenderRequest $request, ContentRenderer $renderer): JsonResponse
    {
        $block = new Block(
            $request->blockName,
            $request->attributes
        );

        return $this->json($renderer->renderEditor($block));
    }
}
