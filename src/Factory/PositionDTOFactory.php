<?php declare(strict_types=1);

namespace App\Factory;

use App\DTO\PositionDTO;
use App\Entity\Position;

class PositionDTOFactory
{
    public function createFieldsForClosedPosition(Position $position):PositionDTO
    {
        $state = $position->getLastState();

        $entryLevel = $position->getInitialState()->getPriceLevel();
        $entryTime = $position->getInitialState()->getTime();
        $exitLevel = $position->getExitLevel();
        $exitTime = $position->getLastState()->getTime();
        $combinedVolume = $position->getCombinedVolume();

        return  new PositionDTO(
            $position->getId(),
            $entryLevel,
            $entryTime,
            $state->getSymbol(),
            $state->getType(),
            $combinedVolume,
            $state->getStopLoss(),
            $state->getCommission(),
            $exitTime,
            $exitLevel,
            $state->getDividend(),
            $state->getSwap(),
            $state->getProfit(),
            $state->getSystem(),
            $state->getStrategy(),
            $state->getAssetClass(),
            $state->getGrade(),
            $state->getState()
        );
    }

    public function createForOpenPosition(Position $position): PositionDTO
    {
        $state = $position->getLastState();

        $entryLevel = $position->getEntryLevel();
        $entryTime = $position->getEntryTime();
        $exitLevel = null;
        $exitTime = null;

        return new PositionDTO(
            $position->getId(),
            $entryLevel,
            $entryTime,
            $state->getSymbol(),
            $state->getType(),
            $state->getVolume(),
            $state->getStopLoss(),
            $state->getCommission(),
            $exitTime,
            $exitLevel,
            $state->getDividend(),
            $state->getSwap(),
            $state->getProfit(),
            $state->getSystem(),
            $state->getStrategy(),
            $state->getAssetClass(),
            $state->getGrade(),
            $state->getState()
        );
    }
}