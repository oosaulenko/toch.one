<?php

namespace App\Form;

use App\Utility\LanguagesInterface;
use Oosaulenko\MediaBundle\Form\Type\MediaChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    public function __construct(
        protected LanguagesInterface $languages
    ) { }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('siteName', TextType::class, [
                'label' => 'Site name',
                'row_attr' => [
                  'class' => 'form-group',
                ],
                'label_attr' => [
                    'class' => 'form-control-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('siteLogo', MediaChoiceType::class, [
                'label' => 'Logo',
            ])
            ->add('siteLangs', ChoiceType::class, [
                'label' => 'Languages support',
                'expanded' => true,
                'row_attr' => [
                    'class' => 'form-group field-siteLangs',
                ],
                'label_attr' => [
                    'class' => 'form-control-label',
                ],
                'multiple' => true,
                'choices' => $this->languages->getLanguages(true),
            ])
            ->add('siteDefaultLang', ChoiceType::class, [
                'label' => 'Default language',
                'row_attr' => [
                    'class' => 'form-group',
                ],
                'label_attr' => [
                    'class' => 'form-control-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => $this->languages->getLanguages(true),
            ])
        ;
    }
}
