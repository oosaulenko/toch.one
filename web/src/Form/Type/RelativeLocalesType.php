<?php

namespace App\Form\Type;

use App\Entity\Page;
use App\Utility\LanguagesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\LocaleSwitcher;

class RelativeLocalesType extends AbstractType
{
    public function __construct(
        protected LanguagesInterface     $languages
    ) { }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = (!empty($options['entity'])) ? $options['entity'] : 'App\Entity\Page';

        $builder
            ->add('locale', HiddenType::class, [
                'attr' => [
                    'class' => 'field-collection-item-title',
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($entity) {
            $form = $event->getForm();
            $data = $event->getData();

            $locale = $data['locale'];

            $form->add('entity', EntityType::class, [
                'class' => $entity,
                'label' => false,
                'choice_label' => 'title',
                'placeholder' => 'None selected page',
                'choice_value' => function ($id) {
                    if (is_int($id) || !$id) return $id;
                    return $id->getId();
                },
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->where('e.locale = :locale')
                        ->setParameter('locale', $locale);
                },
            ]);

            $form->add('entity_tmp', EntityType::class, [
                'class' => $entity,
                'label' => false,
                'choice_label' => 'title',
                'placeholder' => 'None selected page',
                'attr' => [
                    'class' => 'is-hidden',
                ],
                'choice_value' => function ($id) {
                    if (is_int($id) || !$id) return $id;
                    return $id->getId();
                },
                'query_builder' => function (EntityRepository $er) use ($locale) {
                    return $er->createQueryBuilder('e')
                        ->where('e.locale = :locale')
                        ->setParameter('locale', $locale);
                },
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entity' => null,
        ]);

        $resolver->addAllowedTypes('entity', ['null', 'string']);
    }
}
