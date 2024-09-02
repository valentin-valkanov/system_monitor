<?php

namespace App\Repository;

use App\Entity\Position;
use App\Entity\PositionState;
use App\Service\PositionProcessor;
use App\Utils\DateUtils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Position>
 */
class PositionRepository extends ServiceEntityRepository
{
    private PositionProcessor $processor;

    public function __construct(ManagerRegistry $registry, PositionProcessor $processor)
    {
        parent::__construct($registry, Position::class);
        $this->processor = $processor;
    }

    public function findOpenPositions(): array
    {
        $positions = $this->createQueryBuilder('p')
            ->innerJoin('p.positionStates', 'ps')
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();

        return $this->processor->processOpenPositions($positions);
    }

    public function findClosedPositionsForCurrentWeek(): array
    {
        [$startOfWeek, $endOfWeek] = DateUtils::getCurrentWeekRange();

        $positions = $this->createQueryBuilder('p')
            ->innerJoin('p.positionStates', 'ps')
            ->where('ps.state = :closed')
            ->andWhere('ps.time BETWEEN :start AND :end')
            ->setParameter('closed', PositionState::STATE_CLOSED)
            ->setParameter('start', $startOfWeek)
            ->setParameter('end', $endOfWeek)
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();

        return $this->processor->processClosedPositions($positions);
    }

    public function findAllClosedPositions(): array
    {
        $positions = $this->createQueryBuilder('p')
            ->innerJoin('p.positionStates', 'ps')
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();

        return $this->processor->processClosedPositions($positions);
    }
}
