<?php

namespace App\Repository;

use App\Entity\DaysOff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DaysOff>
 */
class DaysOffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DaysOff::class);
    }

    public function findAllSortedById(): array
    {
        return $this
            ->createQueryBuilder('days_off')
            ->orderBy('days_off.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
