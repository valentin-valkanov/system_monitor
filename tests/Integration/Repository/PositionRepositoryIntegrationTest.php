<?php declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Position;
use App\Factory\PositionDTOFactory;
use App\Repository\PositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PositionRepositoryIntegrationTest extends KernelTestCase
{
    use ResetDatabase, Factories;
    public function testItCanCreatePosition(): void
    {
        self::bootKernel();

        $position = new Position;

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        assert($entityManager instanceof EntityManagerInterface);
        $entityManager->persist($position);
        $entityManager->flush();

        $this->assertInstanceOf(Position::class, $position);
    }

}