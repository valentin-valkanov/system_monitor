<?php declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Position;
use App\Entity\PositionState;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PositionIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testPositionEntityCanBePersistedAndRetrieved()
    {
        /* Arrange */
        $position = new Position();
        $positionState = new PositionState();
        $position->addPositionState($positionState);

        /* Act */
        // Persist entity to the database
        $this->entityManager->persist($position);
        $this->entityManager->flush();

        // Clear and retrieve from the database
        $this->entityManager->clear();
        $retrievedPosition = $this->entityManager->getRepository(Position::class)->find($position->getId());

        /* Assert */
        $this->assertSame($position->getId(), $retrievedPosition->getId());
        $this->assertCount(1, $retrievedPosition->getPositionStates());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
//        $this->entityManager = null;
    }
}