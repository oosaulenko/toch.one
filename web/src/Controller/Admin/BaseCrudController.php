<?php

namespace App\Controller\Admin;

use App\Admin\Field\TitleField;
use App\Form\Type\RelativeLocalesType;
use App\Utility\DateView;
use App\Utility\LanguagesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Translation\LocaleSwitcher;

abstract class BaseCrudController extends AbstractCrudController
{
    use DateView;

    public function __construct(
        protected LocaleSwitcher     $localeSwitcher,
        protected LanguagesInterface $languages
    ) { }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $locale = $this->localeSwitcher->getLocale();

        if ($locale) {
            $qb
                ->andWhere('entity.locale = :locale')
                ->setParameter('locale', $locale);
        }

        return $qb;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->addFormTheme('@EasyGutenberg/form/gutenberg_widget.html.twig')
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        $entity = new (static::getEntityFqcn())();
        $fields = [];

        $fields[5] = TitleField::new('title');
        $fields[90] = DateField::new('updatedAt')
            ->setLabel('Updated')->onlyOnIndex()
            ->formatValue(function ($value) {
                return $this->humanDate($value);
            });
        $fields[91] = DateField::new('createdAt')
            ->setLabel('Created')->onlyOnIndex()
            ->formatValue(function ($value) {
                return $this->humanDate($value);
            });

        if(method_exists($entity, 'getSlug')) {
            $fields[40] = SlugField::new('slug')
                ->onlyOnForms()
                ->setTargetFieldName('title')
                ->setHelp('The slug is used in the URL to identify this category.')
                ->setColumns(12);
        }

        if(method_exists($entity, 'getRelativeLocales')) {
            $fields[50] = CollectionField::new('relativeLocales')
                ->setEntryType(RelativeLocalesType::class)
                ->setFormTypeOption('entry_options', ['entity' => static::getEntityFqcn()])
                ->allowAdd(false)
                ->allowDelete(false)
                ->setColumns(12);

            if ($pageName === Crud::PAGE_INDEX) {
                $fields[50]->formatValue(function ($value) {
                    if(!is_array($value)) return '';

                    $locales = array_map(function ($item) {
                        if($item['locale'] == $this->localeSwitcher->getLocale() || !$item['entity']) return '';
                        return '<span class="badge badge-secondary">' . $item['locale'] . '</span>';
                    }, $value);

                    return implode('', $locales);
                });
            }
        }

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        $entity_type = new (static::getEntityFqcn())();

        if(method_exists($entity_type, '_actions')) {
            foreach ($entity_type->_actions() as $type => $action) {
                if($type == 'view') {
                    $action = Action::new('view', 'View')
                        ->addCssClass('text-success')
                        ->setIcon('fa fa-eye')
                        ->setHtmlAttributes(['target' => '_blank'])
                        ->linkToRoute($action, function ($entity): array {
                            if(method_exists($entity, 'isMain') && $entity->isMain()) return [];
                            return ['slug' => $entity->getSlug()];
                        });
                    $actions->add(Crud::PAGE_INDEX, $action);
                    $actions->add(Crud::PAGE_EDIT, $action);
                }

                if($type == 'clone') {
                    $action = Action::new('clone', 'Clone')
                        ->addCssClass('text-warning')
                        ->setIcon('fa fa-clone')
                        ->setHtmlAttributes(['target' => '_blank'])
                        ->linkToCrudAction('cloneEntity');;
                    $actions->add(Crud::PAGE_INDEX, $action);
                    $actions->add(Crud::PAGE_EDIT, $action);
                }
            }
        }

        return $actions;
    }

    public function cloneEntity(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $entityId = $context->getRequest()->query->get('entityId');
        $entity = $entityManager->getRepository(static::getEntityFqcn())->find($entityId);

        if(!$entity) {
            throw $this->createNotFoundException('Entity not found');
        }

        $clonedEntity = clone $entity;

        if(method_exists($clonedEntity, 'setId')) {
            $clonedEntity->setId(null);
        }

        if(method_exists($clonedEntity, 'setTitle')) {
            $clonedEntity->setTitle($entity->getTitle() . ' - Copy');
        }

        if(method_exists($clonedEntity, 'setStatus')) {
            $clonedEntity->setStatus('draft');
        }

        if(method_exists($clonedEntity, 'setSlug')) {
            $clonedEntity->setSlug($entity->getSlug() . '-' . time());
        }

        if(method_exists($clonedEntity, 'setCreatedAtDefault')) {
            $clonedEntity->setCreatedAtDefault();
        }

        if(method_exists($clonedEntity, 'setUpdatedAtDefault')) {
            $clonedEntity->setUpdatedAtDefault();
        }

        if(method_exists($clonedEntity, 'setUser')) {
            $clonedEntity->setUser($this->getUser());
        }

        if(method_exists($clonedEntity, 'setMain')) {
            $clonedEntity->setMain(false);
        }

        if(method_exists($clonedEntity, 'setRelativeLocales')) {
            $clonedEntity->setRelativeLocales(null);
        }

        $entityManager->persist($clonedEntity);
        $entityManager->flush();

        $url = $adminUrlGenerator
            ->setController($context->getRequest()->query->get('crudControllerFqcn'))
            ->setAction('edit')
            ->setEntityId($clonedEntity->getId())
            ->generateUrl();

        $this->addFlash('success', 'Entity cloned successfully');

        return new RedirectResponse($url);
    }

    protected function sortByKeyFields(iterable $fields): iterable
    {
        $fields = iterator_to_array($fields);
        ksort($fields);

        return $fields;
    }

    public function edit(AdminContext $context)
    {
        $entityInstance = $context->getEntity()->getInstance();

        if(method_exists($entityInstance, 'getRelativeLocales')) {
            $relativeLocales = [];

            if (!$entityInstance->getRelativeLocales()) {
                $relativeLocales = $this->prepareRelativeLocales($relativeLocales);
            } else {
                foreach ($entityInstance->getRelativeLocales() as $relativeLocale) {
                    $locale = $relativeLocale['locale'];

                    $relativeLocales[$locale] = [
                        'locale' => $locale,
                        'entity' => $relativeLocale['entity'],
                        'entity_tmp' => $relativeLocale['entity'],
                    ];
                }

                $relativeLocales = array_merge(
                    $relativeLocales,
                    $this->prepareRelativeLocales($relativeLocales)
                );
            }

            unset($relativeLocales[$this->localeSwitcher->getLocale()]);
            $relativeLocales = array_values($relativeLocales);
            $entityInstance->setRelativeLocales($relativeLocales);
        }

        return parent::edit($context);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance = $this->updateEntityInstance($entityManager, $entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance = $this->updateEntityInstance($entityManager, $entityInstance);

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if(method_exists($entityInstance, 'getRelativeLocales')) {
            $repository = $entityManager->getRepository(static::getEntityFqcn());

            foreach ($entityInstance->getRelativeLocales() as $item) {
                if(!$item['entity']) continue;

                $entity = $repository->findById($item['entity']);
                if(!$entity) continue;

                $entity->setRelativeLocales(array_filter($entity->getRelativeLocales(),
                    function ($itemTmp) use ($entityInstance) {
                        return $itemTmp['entity'] != $entityInstance->getId();
                    }));
                $entity->setUpdatedAtDefault();
                $entityManager->persist($entity);
            }
        }

        $entityManager->remove($entityInstance);
        $entityManager->flush();
    }

    protected function updateEntityInstance(EntityManagerInterface $entityManager, $entityInstance)
    {
        if(method_exists($entityInstance, 'setUser') && !$entityInstance->getUser()) {
            $entityInstance->setUser($this->getUser());
        }

        if(method_exists($entityInstance, 'setLocale') && !$entityInstance->getLocale()) {
            $entityInstance->setLocale($this->localeSwitcher->getLocale());
        }

        if(method_exists($entityInstance, 'setCreatedAtDefault') && !$entityInstance->getCreatedAt()) {
            $entityInstance->setCreatedAtDefault();
        }

        if(method_exists($entityInstance, 'setUpdatedAtDefault')) {
            $entityInstance->setUpdatedAtDefault();
        }

        if(method_exists($entityInstance, 'getRelativeLocales')) {
            $relativeLocales = $this->updateRelativeLocales($entityManager, $entityInstance);
            $entityInstance->setRelativeLocales($relativeLocales);
        }

        return $entityInstance;
    }

    private function updateRelativeLocales(EntityManagerInterface $entityManager, $entityInstance): array
    {
        $repository = $entityManager->getRepository(static::getEntityFqcn());

        $relativeLocales = [
            $this->localeSwitcher->getLocale() => [
                'locale' => $this->localeSwitcher->getLocale(),
                'entity' => $entityInstance->getId()
            ]
        ];

        if(!$entityInstance->getRelativeLocales()) {
            return $relativeLocales;
        }

        foreach ($entityInstance->getRelativeLocales() as $item) {
            $relativeLocales[$item['locale']] = [
                'locale' => $item['locale'],
                'entity' => ($item['entity']) ? $item['entity']->getId() : ''
            ];

            if($item['entity_tmp'] && $item['entity_tmp'] !== $item['entity']) {
                $__entity = $item['entity_tmp'];
                $__entity->setRelativeLocales(array_filter($__entity->getRelativeLocales(),
                    function ($itemTmp) use ($entityInstance) {
                        return $itemTmp['entity'] != $entityInstance->getId();
                    }));
                $__entity->setUpdatedAtDefault();
                $entityManager->persist($__entity);
            }

            // Update relative locales for child entities
            if($item['entity']) {
                $__items = $relativeLocales;

                foreach ($item['entity']->getRelativeLocales() as $item_child) {
                    $locale = $item_child['locale'];
                    $entity = $item_child['entity'];

                    if(!$__items[$locale]) {
                        $__items[$locale] = [
                            'locale' => $locale,
                            'entity' => $entity
                        ];
                    } else {
                        if($__items[$locale]['entity'] != $entity && !empty($entity)) {
                            $old_entity = $repository->findById($entity);
                            $sub_relative_locales_tmp = array_filter($old_entity->getRelativeLocales(), function($old_item) use ($item) {
                                return $old_item['entity'] != $item['entity']->getId();
                            });

                            $old_entity->setRelativeLocales($sub_relative_locales_tmp);
                            $old_entity->setUpdatedAtDefault();
                            $entityManager->persist($old_entity);
                        }
                    }
                }

                $item['entity']->setRelativeLocales(array_values($__items));
                $item['entity']->setUpdatedAtDefault();
                $entityManager->persist($item['entity']);
            }
        }


        return array_values($relativeLocales);
    }

    private function prepareRelativeLocales(array $relativeLocales = []): array
    {
        foreach ($this->languages->getSupportLangs() as $lang_code => $lang_name) {
            if (isset($relativeLocales[$lang_code])) continue;

            $relativeLocales[$lang_code] = [
                'locale' => $lang_code,
            ];
        }

        return $relativeLocales;
    }
}