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
        // Fetch all positions with at least one state
        $positions = $this->createQueryBuilder('p')
            ->innerJoin('p.positionStates', 'ps')
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();

        $openPositions = [];

        foreach ($positions as $position) {
            $lastState = $position->getLastState();
            if (
                $lastState &&
                (
                    $lastState->getState() === PositionState::STATE_OPENED ||
                    $lastState->getState() === PositionState::STATE_PARTIALLY_CLOSED ||
                    $lastState->getState() === PositionState::STATE_SCALE_IN
                )
                ) {
                $positionDTO = $position->addFieldsToOpenPositions();
                $openPositions[] = $positionDTO;
            }
        }

        return $openPositions;
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

        $closedPositions = [];

        foreach ($positions as $position) {
            $closedPositions = array_merge($closedPositions, $position->printClosedPositionsForCurrentWeek());
        }

        return $closedPositions;
    }

    public function findAllClosedPositions(): array
    {
        // Fetch all positions with at least one state
        $positions = $this->createQueryBuilder('p')
            ->innerJoin('p.positionStates', 'ps')
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();

        $openPositions = [];

        foreach ($positions as $position) {
            dump($position);
            $lastState = $position->getLastState();

            if ($lastState && $lastState->getState() === PositionState::STATE_CLOSED) { //check if there is a state and if its state is 'closed'
                $positionDTO = $position->addFieldsToClosedPositions();
                $openPositions[] = $positionDTO;
            }
        }

        return $openPositions;
    }
}
