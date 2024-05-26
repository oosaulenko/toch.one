<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => true,
            'choices' => [
                'Draft' => 'draft',
                'Published' => 'published',
                'Archived' => 'archived',
            ],
        ]);
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}
