<?php

namespace App\Tests\Entity;

use App\Entity\Position;
use App\Entity\PositionState;
use App\Repository\PositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    public function testConstructor(): void
    {

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $positionRepository = $this->createMock(PositionRepository::class);

        $position = new Position($entityManager, $positionRepository);

        $this->assertInstanceOf(ArrayCollection::class, $position->getPositionStates());
    }

    public function testGetId(): void
    {
        $position = new Position();

        $reflectionProperty = new \ReflectionProperty(Position::class, 'id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($position, 2);

        $this->assertEquals(2, $position->getId());
    }

    public function testAddPositionState()
    {
        //Setup
        $position = new Position();
        $positionState = new PositionState();

        //Do Something
        $position->addPositionState($positionState);

        //Make Assertions
        $this->assertContains($positionState, $position->getPositionStates());
        $this->assertSame($position, $positionState->getPosition());
    }

    public function testGetLastState()
    {
        //Setup
        $states = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', ],
            ['time' => '2024-06-04 12:00', 'state' => 'opened', ]
        ];

        $position = $this->createPositionWithStates($states);

        //Do Something
        $lastState = $position->getLastState();

        //Make Assertions
        $this->assertInstanceOf(PositionState::class, $lastState);
        $this->assertEquals( new \DateTimeImmutable('2024-06-04 12:00'), $lastState->getTime());
    }

    public function testGetInitialState()
    {
        //Setup
        $states = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', ],
            ['time' => '2024-06-04 12:00', 'state' => 'opened', ]
        ];

        $position = $this->createPositionWithStates($states);

        //Do Something
        $initialState = $position->getInitialState();

        //Make Assertions
        $this->assertInstanceOf(PositionState::class, $initialState);
        $this->assertEquals( new \DateTimeImmutable('2024-06-03 12:00'), $initialState->getTime());
    }

    public function testGetEntryTime()
    {
        //Setup
        $states = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', ],
            ['time' => '2024-06-04 12:00', 'state' => 'scale_in', ],
            ['time' => '2024-06-05 12:00', 'state' => 'partially_closed', ],
            ['time' => '2024-06-06 12:00', 'state' => 'closed', ],
        ];

        $position = $this->createPositionWithStates($states);

        //Do Something
        $entryTime = $position->getEntryTime();

        //Make Assertions
        $this->assertEquals(new \DateTimeImmutable('2024-06-03 12:00'), $entryTime);
    }

    public function testGetEntryLevel()
    {
        //Setup
        $statesWithScaleInState = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', 'priceLevel' => 2, 'volume' => 1],
            ['time' => '2024-06-04 12:00', 'state' => 'scale_in', 'priceLevel' => 2.5, 'volume' => 1.5],
            ['time' => '2024-06-04 12:00', 'state' => 'scale_in', 'priceLevel' => 3, 'volume' => 2.0],
        ];

        $ScaleInPosition = $this->createPositionWithStates($statesWithScaleInState);

        $statesWithoutScaleInState = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', 'priceLevel' => 2, 'volume' => 1],
            ['time' => '2024-06-04 12:00', 'state' => 'closed', 'priceLevel' => 3, 'volume' => 2],
        ];

        $position = $this->createPositionWithStates($statesWithoutScaleInState);

        //Do Something
        $scaleInEntryLevel = $ScaleInPosition->getEntryLevel();
        $entryLevel = $position->getEntryLevel();

        //Make Assertions
        $this->assertEquals(2.61, $scaleInEntryLevel);
        $this->assertEquals(2.0, $entryLevel);
    }

    public function testGetExitLevel()
    {
        //Setup
        $statesWithPartiallyClosedState = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', 'priceLevel' => 1, 'volume' => 2],
            ['time' => '2024-06-03 12:00', 'state' => 'partially_closed', 'priceLevel' => 2, 'volume' => 0.7],
            ['time' => '2024-06-03 12:00', 'state' => 'partially_closed', 'priceLevel' => 3, 'volume' => 0.7],
            ['time' => '2024-06-04 12:00', 'state' => 'closed', 'priceLevel' => 4, 'volume' => 0.6],
        ];

        $positionWithPartialExit = $this->createPositionWithStates($statesWithPartiallyClosedState);

        $states = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', 'priceLevel' => 1, 'volume' => 2],
            ['time' => '2024-06-04 12:00', 'state' => 'closed', 'priceLevel' => 3, 'volume' => 2],
        ];

        $positionWithoutPartialExit = $this->createPositionWithStates($states);

        //Do Something
        $combinedExit = $positionWithPartialExit->getExitLevel();
        $exit = $positionWithoutPartialExit->getExitLevel();

        //Make Assertions
        $this->assertEquals(2.95, $combinedExit);
        $this->assertEquals(3, $exit);
    }

    public function testGetClosedPositionVolume()
    {
        //Setup
        $states = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', 'priceLevel' => 2, 'volume' => 1],
            ['time' => '2024-06-04 12:00', 'state' => 'scale_in', 'priceLevel' => 2.5, 'volume' => 1.5],
            ['time' => '2024-06-04 13:00', 'state' => 'scale_in', 'priceLevel' => 3, 'volume' => 2.0],
            ['time' => '2024-06-05 12:00', 'state' => 'closed', 'priceLevel' => 5, 'volume' => 4.5],
        ];

        $closedPosition = $this->createPositionWithStates($states);

        //Do Something
        $volume = $closedPosition->getClosedPositionVolume();

        //Make Assertion

        $this->assertEquals(4.5, $volume);
    }

    public function testGetOpenPositionVolume()
    {
        //Setup
        $states = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', 'priceLevel' => 2, 'volume' => 1],
            ['time' => '2024-06-04 12:00', 'state' => 'scale_in', 'priceLevel' => 2.5, 'volume' => 1.5],
            ['time' => '2024-06-04 13:00', 'state' => 'scale_in', 'priceLevel' => 3, 'volume' => 2.0],

        ];

        $closedPosition = $this->createPositionWithStates($states);

        //Do Something
        $volume = $closedPosition->getOpenPositionVolume();

        //Make Assertion

        $this->assertEquals(4.5, $volume);
    }

    private function createPositionWithStates(array $states): Position
    {
        $position = new Position();
        foreach ($states as $stateData) {
            $state = new PositionState();
            $state->setTime(new \DateTimeImmutable($stateData['time']));
            $state->setState($stateData['state']);
            if(isset($stateData['priceLevel'])){
                $state->setPriceLevel($stateData['priceLevel']);
            }
            if(isset($stateData['volume'])){
                $state->setVolume($stateData['volume']);
            }
            $position->addPositionState($state);
        }
        return $position;
    }
}
