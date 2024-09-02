<?php declare(strict_types=1);

namespace App\Tests\Unit\Service;


use App\DTO\PositionDTO;
use App\Entity\Position;
use App\Entity\PositionState;
use App\Factory\PositionDTOFactory;
use App\Service\PositionProcessor;
use Monolog\Test\TestCase;

class PositionProcessorTest extends TestCase
{
    private $factory;
    private $processor;

    protected function setUp(): void
    {
        // Mocking the PositionDTOFactory
        $this->factory = $this->createMock(PositionDTOFactory::class);

        // Instantiating PositionProcessor with the mocked factory
        $this->processor = new PositionProcessor($this->factory);
    }

    public function testProcessOpenPositionsWithOpenState(): void
    {
        // Create mock Position and PositionState objects
        $position = $this->createMock(Position::class);
        $positionState = $this->createMock(PositionState::class);

        // Mock the Position's getLastState method to return the PositionState
        $position->method('getLastState')->willReturn($positionState);

        // Mock the PositionState's getState method to return STATE_OPENED
        $positionState->method('getState')->willReturn(PositionState::STATE_OPENED);

        // Mock the PositionDTOFactory to return a mock PositionDTO
        $mockPositionDTO = $this->createMock(PositionDTO::class);
        $this->factory->method('createForOpenPosition')->willReturn($mockPositionDTO);

        // Call the method under test
        $result = $this->processor->processOpenPositions([$position]);

        // Assert that the result contains one DTO
        $this->assertCount(1, $result);
    }

    public function testProcessOpenPositionsWithNonOpenState(): void
    {
        $position = $this->createMock(Position::class);
        $positionState = $this->createMock(PositionState::class);

        $position->method('getLastState')->willReturn($positionState);
        $positionState->method('getState')->willReturn(PositionState::STATE_CLOSED);

        // No DTO should be created for closed positions in this case
        $this->factory->expects($this->never())->method('createForOpenPosition');

        $result = $this->processor->processOpenPositions([$position]);

        // Assert that the result is an empty array
        $this->assertEmpty($result);
    }

    public function testProcessClosedPositionsWithClosedState(): void
    {
        $position = $this->createMock(Position::class);
        $positionState = $this->createMock(PositionState::class);

        $position->method('getLastState')->willReturn($positionState);
        $positionState->method('getState')->willReturn(PositionState::STATE_CLOSED);

        // Mock the PositionDTOFactory to return a mock PositionDTO
        $mockPositionDTO = $this->createMock(PositionDTO::class);
        $this->factory->method('createFieldsForClosedPosition')->willReturn($mockPositionDTO);

        $result = $this->processor->processClosedPositions([$position]);

        $this->assertCount(1, $result);
    }

    public function testProcessClosedPositionsWithNonClosedState(): void
    {
        $position = $this->createMock(Position::class);
        $positionState = $this->createMock(PositionState::class);

        $position->method('getLastState')->willReturn($positionState);
        $positionState->method('getState')->willReturn(PositionState::STATE_OPENED);

        $this->factory->expects($this->never())->method('createFieldsForClosedPosition');

        $result = $this->processor->processClosedPositions([$position]);

        $this->assertEmpty($result);
    }
}