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
            ->select('ps')
            ->innerJoin('ps.position', 'p')
            ->where('ps.state IN (:openStates)')
            ->setParameter('openStates', [PositionState::STATE_OPENED, PositionState::STATE_MODIFIED])
            ->andWhere('ps.id IN (
            SELECT MAX(ps2.id)
            FROM App\Entity\PositionState ps2
            WHERE ps2.position = ps.position
            GROUP BY ps2.position
        )')
            ->getQuery()
            ->getResult();
    }

    public function findClosedPositionsForCurrentWeek(): array
    {
        [$startOfWeek, $endOfWeek] = DateUtils::getCurrentWeekRange();

        return $this->createQueryBuilder('ps')
            ->select('ps')
            ->innerJoin('ps.position', 'p')
            ->where('ps.state = :closed')
            ->andWhere('ps.entryTime BETWEEN :startOfWeek AND :endOfWeek')
            ->andWhere('ps.id IN (
            SELECT MAX(ps2.id)
            FROM App\Entity\PositionState ps2
            WHERE ps2.position = ps.position
            GROUP BY ps2.position
        )')
            ->setParameter('closed', PositionState::STATE_CLOSED)
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', $endOfWeek)
            ->getQuery()
            ->getResult();
    }

    public function findLatestClosedPositionStates()
    {
        $subquery = $this->createQueryBuilder('ps2')
            ->select('MAX(ps2.id)')
            ->innerJoin('ps2.position', 'p2')
            ->where('ps2.state = :closed')
            ->groupBy('p2.id')
            ->getDQL();

        return $this->createQueryBuilder('ps')
            ->innerJoin('ps.position', 'p')
            ->where("ps.id IN ({$subquery})")
            ->setParameter('closed', PositionState::STATE_CLOSED)
            ->orderBy('ps.entryTime', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
