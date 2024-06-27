<?php declare(strict_types=1);

namespace App\Factory;

use App\DTO\PositionDTO;
use App\Entity\Position;

class PositionDTOFactory
{
    public function createFieldsForClosedPosition(Position $position):PositionDTO
    {
        $state = $position->getLastState();

        $entryLevel = $position->getEntryLevel();
        $entryTime = $position->getInitialState()->getTime();
        $exitLevel = $position->getExitLevel();
        $exitTime = $position->getLastState()->getTime();
        $volume = $position->getClosedPositionVolume();
        $profit = $position->getProfit();
        $swap = $position->getSwap();

        return  new PositionDTO(
            $position->getId(),
            $entryLevel,
            $entryTime,
            $state->getSymbol(),
            $state->getType(),
            $volume,
            $state->getStopLoss(),
            $state->getCommission(),
            $exitTime,
            $exitLevel,
            $state->getDividend(),
            $swap,
            $profit,
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
        $volume = $position->getOpenPositionVolume();
        $exitLevel = null;
        $exitTime = null;
        $profit = $position->getProfit();
        $swap = $position->getSwap();


        return new PositionDTO(
            $position->getId(),
            $entryLevel,
            $entryTime,
            $state->getSymbol(),
            $state->getType(),
            $volume,
            $state->getStopLoss(),
            $state->getCommission(),
            $exitTime,
            $exitLevel,
            $state->getDividend(),
            $swap,
            $profit,
            $state->getSystem(),
            $state->getStrategy(),
            $state->getAssetClass(),
            $state->getGrade(),
            $state->getState()
        );
    }
}