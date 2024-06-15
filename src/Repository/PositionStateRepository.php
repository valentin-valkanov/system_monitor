<?php

namespace App\Repository;

use App\Entity\PositionState;
use App\Utils\DateUtils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PositionStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PositionState::class);
    }

    public function findOpenPositions(): array
    {
        return $this->createQueryBuilder('ps')
            ->where('ps.exitTime IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findClosedPositionsForCurrentWeek(): array
    {
        [$startOfWeek, $endOfWeek] = DateUtils::getCurrentWeekRange();

        return $this->createQueryBuilder('ps')
            ->where('ps.exitTime BETWEEN :startOfWeek AND :endOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', $endOfWeek)
            ->getQuery()
            ->getResult();
    }
}
