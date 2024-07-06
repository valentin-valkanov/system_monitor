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


    public function testGetEntryLevelWithScaleInState()
    {
        //Setup
        $states = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', 'priceLevel' => 2, 'volume' => 1],
            ['time' => '2024-06-04 12:00', 'state' => 'scale_in', 'priceLevel' => 3, 'volume' => 2],
        ];

        $position = $this->createPositionWithStates($states);

        //Do Something
        $entryLevel = $position->getEntryLevel();

        //Make Assertions

        $this->assertEquals(2.67, $entryLevel);
    }

    public function testGetEntryLevelWithoutScaleInState()
    {
        //Setup
        $states = [
            ['time' => '2024-06-03 12:00', 'state' => 'opened', 'priceLevel' => 2, 'volume' => 1],
            ['time' => '2024-06-04 12:00', 'state' => 'closed', 'priceLevel' => 3, 'volume' => 2],
        ];

        $position = $this->createPositionWithStates($states);

        //Do Something
        $entryLevel = $position->getEntryLevel();

        //Make Assertions
        $this->assertEquals(2, $entryLevel);
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
                $state->setPriceLevel($stateData['volume']);
            }
            $position->addPositionState($state);
        }
        return $position;
    }
}
