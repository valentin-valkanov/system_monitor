<?php

namespace App\Repository;

use App\Entity\Position;
use App\Entity\PositionState;
use App\Utils\DateUtils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Position>
 */
class PositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Position::class);
    }

    public function findOpenPositions(): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.positionStates', 'ps')
            ->where('ps.state IN (:openStates)')
            ->setParameter('openStates', [
                PositionState::STATE_OPENED,
                PositionState::STATE_PARTIALLY_CLOSED,
                PositionState::STATE_SCALE_IN
            ])
            ->andWhere('ps.id IN (
            SELECT MAX(ps2.id)
            FROM App\Entity\PositionState ps2
            WHERE ps2.position = p.id
            GROUP BY ps2.position
        )')
            ->getQuery()
            ->getResult();
    }

    public function findClosedPositionsForCurrentWeek(): array
    {

        [$startOfWeek, $endOfWeek] = DateUtils::getCurrentWeekRange();

        return $this->createQueryBuilder('p')
            ->innerJoin('p.positionStates', 'ps')
            ->where('ps.state = :closed')
            ->andWhere('ps.time BETWEEN :startOfWeek AND :endOfWeek')
            ->andWhere('ps.id IN (
            SELECT MAX(ps2.id)
            FROM App\Entity\PositionState ps2
            WHERE ps2.position = p.id
            GROUP BY ps2.position
        )')
            ->setParameter('closed', PositionState::STATE_CLOSED)
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', $endOfWeek)
            ->getQuery()
            ->getResult();
    }

    public function findLatestClosedPositionStates(): array
    {
        $subquery = $this->getEntityManager()->createQueryBuilder()
            ->select('MAX(ps2.id)')
            ->from(PositionState::class, 'ps2')
            ->where('ps2.state = :closed')
            ->groupBy('ps2.position')
            ->getDQL();

        return $this->createQueryBuilder('p')
            ->innerJoin('p.positionStates', 'ps')
            ->where("ps.id IN ({$subquery})")
            ->setParameter('closed', PositionState::STATE_CLOSED)
            ->orderBy('ps.time', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
