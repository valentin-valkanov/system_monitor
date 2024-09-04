<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Position;
use App\Entity\PositionState;
use PHPUnit\Framework\TestCase;

class PositionStateTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $positionState = new PositionState();

        // Test setting and getting time
        $time = new \DateTimeImmutable('2024-07-04 12:00');
        $positionState->setTime($time);
        $this->assertSame($time, $positionState->getTime());

        // Test setting and getting symbol
        $symbol = 'EURUSD';
        $positionState->setSymbol($symbol);
        $this->assertSame($symbol, $positionState->getSymbol());

        // Test setting and getting type
        $type = 'type1';
        $positionState->setType($type);
        $this->assertSame($type, $positionState->getType());

        // Test setting and getting volume
        $volume = 1.5;
        $positionState->setVolume($volume);
        $this->assertSame($volume, $positionState->getVolume());

        // Test setting and getting priceLevel
        $priceLevel = 1.2345;
        $positionState->setPriceLevel($priceLevel);
        $this->assertSame($priceLevel, $positionState->getPriceLevel());

        // Test setting and getting stopLoss
        $stopLoss = 1.2000;
        $positionState->setStopLoss($stopLoss);
        $this->assertSame($stopLoss, $positionState->getStopLoss());

        // Test setting and getting commission
        $commission = 10.0;
        $positionState->setCommission($commission);
        $this->assertSame($commission, $positionState->getCommission());

        // Test setting and getting dividend
        $dividend = 2.0;
        $positionState->setDividend($dividend);
        $this->assertSame($dividend, $positionState->getDividend());

        // Test setting and getting swap
        $swap = -0.5;
        $positionState->setSwap($swap);
        $this->assertSame($swap, $positionState->getSwap());

        // Test setting and getting profit
        $profit = 100.0;
        $positionState->setProfit($profit);
        $this->assertSame($profit, $positionState->getProfit());

        // Test setting and getting system
        $system = 'System1';
        $positionState->setSystem($system);
        $this->assertSame($system, $positionState->getSystem());

        // Test setting and getting strategy
        $strategy = 'Strategy1';
        $positionState->setStrategy($strategy);
        $this->assertSame($strategy, $positionState->getStrategy());

        // Test setting and getting assetClass
        $assetClass = 'currencies';
        $positionState->setAssetClass($assetClass);
        $this->assertSame($assetClass, $positionState->getAssetClass());

        // Test setting and getting grade
        $grade = 'A';
        $positionState->setGrade($grade);
        $this->assertSame($grade, $positionState->getGrade());

        // Test setting and getting state
        $state = PositionState::STATE_OPENED;
        $positionState->setState($state);
        $this->assertSame($state, $positionState->getState());

        // Test setting and getting position
        $position = new Position();
        $positionState->setPosition($position);
        $this->assertSame($position, $positionState->getPosition());
    }

    public function testConstants()
    {
        $this->assertSame('opened', PositionState::STATE_OPENED);
        $this->assertSame('partially_closed', PositionState::SCALE_OUT);
        $this->assertSame('scale_in', PositionState::STATE_SCALE_IN);
        $this->assertSame('closed', PositionState::STATE_CLOSED);

        $this->assertSame('none', PositionState::GRADE_NONE);
        $this->assertSame('A', PositionState::GRADE_A);
        $this->assertSame('B', PositionState::GRADE_B);
        $this->assertSame('C', PositionState::GRADE_C);
    }
}