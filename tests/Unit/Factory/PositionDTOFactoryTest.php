<?php declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\DTO\PositionDTO;
use App\Entity\Position;
use App\Entity\PositionState;
use App\Factory\PositionDTOFactory;
use PHPUnit\Framework\TestCase;

class PositionDTOFactoryTest extends TestCase
{
    public function testCreateFieldsForClosedPosition()
    {
        // Arrange
        $position = $this->createMock(Position::class);
        $state = $this->createMock(PositionState::class);

        $position->method('getLastState')->willReturn($state);
        $position->method('getEntryLevel')->willReturn(1.2345);
        $position->method('getInitialState')->willReturn($state);
        $position->method('getExitLevel')->willReturn(1.3456);
        $position->method('getClosedPositionVolume')->willReturn(1000.0);
        $position->method('calculateProfit')->willReturn(100.0);
        $position->method('calculateSwap')->willReturn(0.5);
        $position->method('calculateCommission')->willReturn(10.0);
        $position->method('getId')->willReturn(1);

        $state->method('getSymbol')->willReturn('EURUSD');
        $state->method('getType')->willReturn('BUY');
        $state->method('getTime')->willReturn(new \DateTimeImmutable('2024-07-04 12:00'));
        $state->method('getStopLoss')->willReturn(1.2000);
        $state->method('getDividend')->willReturn(5.0);
        $state->method('getSystem')->willReturn('System1');
        $state->method('getStrategy')->willReturn('Strategy1');
        $state->method('getAssetClass')->willReturn('currencies');
        $state->method('getGrade')->willReturn('A');
        $state->method('getState')->willReturn('closed');

        $factory = new PositionDTOFactory();

        // Act
        $dto = $factory->createFieldsForClosedPosition($position);

        // Assert
        $this->assertInstanceOf(PositionDTO::class, $dto);
        $this->assertEquals(1, $dto->getPositionId());
        $this->assertEquals(1.2345, $dto->getEntryLevel());
        $this->assertEquals(new \DateTimeImmutable('2024-07-04 12:00'), $dto->getEntryTime());
        $this->assertEquals('EURUSD', $dto->getSymbol());
        $this->assertEquals('BUY', $dto->getType());
        $this->assertEquals(1000.0, $dto->getVolume());
        $this->assertEquals(1.2000, $dto->getStopLoss());
        $this->assertEquals(10.0, $dto->getCommission());
        $this->assertEquals(new \DateTimeImmutable('2024-07-04 12:00'), $dto->getExitTime());
        $this->assertEquals(1.3456, $dto->getExitLevel());
        $this->assertEquals(5.0, $dto->getDividend());
        $this->assertEquals(0.5, $dto->getSwap());
        $this->assertEquals(100.0, $dto->getProfit());
        $this->assertEquals('System1', $dto->getSystem());
        $this->assertEquals('Strategy1', $dto->getStrategy());
        $this->assertEquals('currencies', $dto->getAssetClass());
        $this->assertEquals('A', $dto->getGrade());
        $this->assertEquals('closed', $dto->getState());
    }

    public function testCreateForOpenPosition()
    {
        // Arrange
        $position = $this->createMock(Position::class);
        $state = $this->createMock(PositionState::class);

        $position->method('getLastState')->willReturn($state);
        $position->method('getEntryLevel')->willReturn(1.2345);
        $position->method('getEntryTime')->willReturn(new \DateTimeImmutable('2024-07-04 12:00'));
        $position->method('getOpenPositionVolume')->willReturn(1000.0);
        $position->method('calculateProfit')->willReturn(100.0);
        $position->method('calculateSwap')->willReturn(0.5);
        $position->method('calculateCommission')->willReturn(10.0);
        $position->method('getId')->willReturn(1);

        $state->method('getSymbol')->willReturn('EURUSD');
        $state->method('getType')->willReturn('BUY');
        $state->method('getStopLoss')->willReturn(1.2000);
        $state->method('getDividend')->willReturn(5.0);
        $state->method('getSystem')->willReturn('System1');
        $state->method('getStrategy')->willReturn('Strategy1');
        $state->method('getAssetClass')->willReturn('currencies');
        $state->method('getGrade')->willReturn('A');
        $state->method('getState')->willReturn('opened');

        $factory = new PositionDTOFactory();

        // Act
        $dto = $factory->createForOpenPosition($position);

        // Assert
        $this->assertInstanceOf(PositionDTO::class, $dto);
        $this->assertEquals(1, $dto->getPositionId());
        $this->assertEquals(1.2345, $dto->getEntryLevel());
        $this->assertEquals(new \DateTimeImmutable('2024-07-04 12:00'), $dto->getEntryTime());
        $this->assertEquals('EURUSD', $dto->getSymbol());
        $this->assertEquals('BUY', $dto->getType());
        $this->assertEquals(1000.0, $dto->getVolume());
        $this->assertEquals(1.2000, $dto->getStopLoss());
        $this->assertEquals(10.0, $dto->getCommission());
        $this->assertNull($dto->getExitTime());
        $this->assertNull($dto->getExitLevel());
        $this->assertEquals(5.0, $dto->getDividend());
        $this->assertEquals(0.5, $dto->getSwap());
        $this->assertEquals(100.0, $dto->getProfit());
        $this->assertEquals('System1', $dto->getSystem());
        $this->assertEquals('Strategy1', $dto->getStrategy());
        $this->assertEquals('currencies', $dto->getAssetClass());
        $this->assertEquals('A', $dto->getGrade());
        $this->assertEquals('opened', $dto->getState());
    }
}