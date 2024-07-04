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

        $this->assertInstanceOf(ArrayCollection::class, $position->getPositionStates($position));
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
        $this->assertContains($positionState, $position->getPositionStates($position));
        $this->assertSame($position, $positionState->getPosition());
    }
}
