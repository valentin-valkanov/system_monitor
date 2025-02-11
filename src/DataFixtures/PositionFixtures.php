<?php

namespace App\DataFixtures;

use App\Entity\Position;
use App\Entity\PositionState;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PositionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create a Position
        $position = new Position();
        $manager->persist($position);

        // Create a PositionState
        $positionState = new PositionState();
        $positionState->setPosition($position);
        $positionState->setTime(new DateTimeImmutable());
        $positionState->setSymbol('EURUSD');
        $positionState->setType('Buy');
        $positionState->setSystem('ST');
        $positionState->setStrategy('Breakout');
        $positionState->setAssetClass('Currencies');
        $positionState->setVolume(0.55);
        $positionState->setPriceLevel(1.0500);
        $positionState->setStopLoss(1.0400);
        $positionState->setCommission(0.0);
        $positionState->setDividend(0.0);
        $positionState->setSwap(5.5);
        $positionState->setProfit(356.45);
        $positionState->setGrade('A');
        $positionState->setState('closed');

        $manager->persist($positionState);

        // Save to database
        $manager->flush();
    }
}
