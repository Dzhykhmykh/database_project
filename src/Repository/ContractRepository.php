<?php

namespace App\Repository;

use App\Entity\Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contract>
 */
class ContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contract::class);
    }

    public function findAllSortedById(): array
    {
        return $this
            ->createQueryBuilder('contract')
            ->orderBy('contract.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
