<?php

namespace App\Form\Type;

use Oosaulenko\MediaBundle\Form\Type\MediaChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'choices' => [
                    'Манікюрні інструменти' => 'MANICURE_TOOLS',
                    'Перукарські інструменти' => 'HAIRDRESSING_TOOLS',
                    'Побутовий інструмент' => 'HOUSEHOLD_TOOLS',
                ],
                'placeholder' => 'Choose category',
                'required' => true,
            ])
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'class' => 'field-collection-item-title',
                ]
            ])
            ->add('price', TextType::class, [
                'label' => 'Price',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
