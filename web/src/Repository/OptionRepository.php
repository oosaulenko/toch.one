<?php

namespace App\Repository;

use App\Entity\Option;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Option>
 */
class OptionRepository extends ServiceEntityRepository implements OptionRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        protected EntityManagerInterface $em
    )
    {
        parent::__construct($registry, Option::class);
    }

    public function getSettings(): ?array
    {
        return self::findAll();
    }

    public function getSetting(string $name): ?Option
    {
        return self::findOneBy(['name' => $name]);
    }

    public function setSetting(string $name, string $value = null, bool $flush = false): void
    {
        $setting = $this->getSetting($name) ?? new Option();
        $setting->setName($name);
        $setting->setValue($value);
        $this->em->persist($setting);

        if($flush) {
            $this->save();
        }
    }

    public function save(): void
    {
        $this->em->flush();
    }
}
