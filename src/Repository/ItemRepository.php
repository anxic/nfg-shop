<?php

declare(strict_types=1);

namespace WolfShop\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WolfShop\Entity\ItemEntity;

/**
 * @extends ServiceEntityRepository<ItemEntity>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemEntity::class);
    }

    /**
     * Finds items by their names.
     *
     * @param string[] $names
     * @return ItemEntity[]
     */
    public function findByNames(array $names): array
    {
        /** @var ItemEntity[] $result */
        $result = $this->createQueryBuilder('i')
            ->where('i.name IN (:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
