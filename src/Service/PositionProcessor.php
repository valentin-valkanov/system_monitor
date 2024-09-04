<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\PositionState;
use App\Factory\PositionDTOFactory;

class PositionProcessor
{
    private PositionDTOFactory $factory;

    public function __construct(PositionDTOFactory $factory)
    {
        $this->factory = $factory;
    }

    public function processOpenPositions(array $positions): array
    {
        $openPositions = [];

        foreach ($positions as $position) {
            $lastState = $position->getLastState();
            if ($lastState && $this->isOpenState($lastState->getState())) {
                $positionDTO = $this->factory->createForOpenPosition($position);
                $openPositions[] = $positionDTO;
            }
        }

        return $openPositions;
    }

    public function processClosedPositions(array $positions): array
    {
        $closedPositions = [];

        foreach ($positions as $position) {
            $lastState = $position->getLastState();
            if ($lastState && $lastState->getState() === PositionState::STATE_CLOSED) {
                $positionDTO = $this->factory->createFieldsForClosedPosition($position);
                $closedPositions[] = $positionDTO;
            }
        }

        return $closedPositions;
    }

    private function isOpenState(string $state): bool
    {
        return in_array($state, [
            PositionState::STATE_OPENED,
            PositionState::SCALE_OUT,
            PositionState::STATE_SCALE_IN,
        ], true);
    }
}