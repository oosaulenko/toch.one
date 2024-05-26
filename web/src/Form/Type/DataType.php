<?php

namespace App\Form\Type;

use App\Form\JsonToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('meta_title', TextType::class, [
                'label' => 'SEO Title',
                'help' => 'The ideal length of meta title is about 60 characters.',
            ])
            ->add('meta_description', TextareaType::class, [
                'label' => 'SEO Description',
                'help' => 'The maximum SEO meta description length can be up to 300 characters',
            ])
            ->add('meta_keywords', TextType::class, [
                'label' => 'Meta Keywords',
                'help' => 'The ideal length of meta keywords is about 60 characters.',
            ])
            ->add('hide_title', ChoiceType::class, [
                'label' => 'Hide Title',
                'help' => 'Hide the title of the page. This is useful when you want to use a custom title in the content.',
                'choices' => [
                    'Yes' => 'yes'
                ],
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'Test'
            // Configure your form options here
        ]);
    }
}
