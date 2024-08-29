<?php declare(strict_types=1);

namespace App\Tests\Unit\DTO;


use App\DTO\PositionDTO;
use PHPUnit\Framework\TestCase;

class PositionDTOTest extends TestCase
{
    public function testConstructorAndGetters()
    {
        // Arrange
        $positionId = 1;
        $entryLevel = 1.1234;
        $entryTime = new \DateTimeImmutable('2024-07-04 12:00');
        $symbol = 'EURUSD';
        $type = 'buy';
        $volume = 0.5;
        $stopLoss = 1.1135;
        $commission = 10.0;
        $exitTime = new \DateTimeImmutable('2024-07-05 12:00');
        $exitLevel = 1.1380;
        $dividend = 5.0;
        $swap = 23.5;
        $profit = 580.0;
        $system = 'STS';
        $strategy = 'trend';
        $assetClass = 'currencies';
        $grade = 'A';
        $state = 'closed';

        // Act
        $positionDTO = new PositionDTO(
            $positionId,
            $entryLevel,
            $entryTime,
            $symbol,
            $type,
            $volume,
            $stopLoss,
            $commission,
            $exitTime,
            $exitLevel,
            $dividend,
            $swap,
            $profit,
            $system,
            $strategy,
            $assetClass,
            $grade,
            $state
        );

        // Assert
        $this->assertEquals($positionId, $positionDTO->getPositionId());
        $this->assertEquals($entryLevel, $positionDTO->getEntryLevel());
        $this->assertEquals($entryTime, $positionDTO->getEntryTime());
        $this->assertEquals($symbol, $positionDTO->getSymbol());
        $this->assertEquals($type, $positionDTO->getType());
        $this->assertEquals($volume, $positionDTO->getVolume());
        $this->assertEquals($stopLoss, $positionDTO->getStopLoss());
        $this->assertEquals($commission, $positionDTO->getCommission());
        $this->assertEquals($exitTime, $positionDTO->getExitTime());
        $this->assertEquals($exitLevel, $positionDTO->getExitLevel());
        $this->assertEquals($dividend, $positionDTO->getDividend());
        $this->assertEquals($swap, $positionDTO->getSwap());
        $this->assertEquals($profit, $positionDTO->getProfit());
        $this->assertEquals($system, $positionDTO->getSystem());
        $this->assertEquals($strategy, $positionDTO->getStrategy());
        $this->assertEquals($assetClass, $positionDTO->getAssetClass());
        $this->assertEquals($grade, $positionDTO->getGrade());
        $this->assertEquals($state, $positionDTO->getState());
    }
}