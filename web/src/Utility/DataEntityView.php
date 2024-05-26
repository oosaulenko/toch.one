<?php

namespace App\Utility;

use App\Utility\DataEntityViewInterface;

class DataEntityView implements DataEntityViewInterface
{

    public function getMeta(object $entity): array
    {
        if (!method_exists($entity, 'getData')) {
            return [
                'meta_title' => $entity->getTitle(),
                'meta_description' => '',
                'meta_keywords' => '',
            ];
        }

        return [
            'meta_title' => ($entity->getData()['meta_title']) ?? $entity->getTitle(),
            'meta_description' => ($entity->getData()['meta_description']) ?? '',
            'meta_keywords' => ($entity->getData()['meta_keywords']) ?? '',
        ];
    }
}