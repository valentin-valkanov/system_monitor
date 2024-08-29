<?php declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\Entity\PositionState;
use App\Form\PositionStateType;
use Symfony\Component\Form\Test\TypeTestCase;

class PositionStateTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {
        //Arrange
        $formData = [
            'time' => '2024.07.08 17:43:40',
            'symbol' => 'EURUSD',
            'type' => 'BUY',
            'volume' => 1000.0,
            'priceLevel' => 1.2345,
            'stopLoss' => 1.2000,
            'commission' => 10.0,
            'dividend' => 5.0,
            'swap' => 0.5,
            'profit' => 100.0,
            'system' => 'System1',
            'strategy' => 'Strategy1',
            'assetClass' => 'currencies',
            'grade' => PositionState::GRADE_A,
            'state' => PositionState::STATE_OPENED,
        ];

        $model = new PositionState();
        $form = $this->factory->create(PositionStateType::class, $model);

        $expected = new PositionState();
        $expected->setTime(\DateTimeImmutable::createFromFormat('Y.m.d H:i:s', $formData['time']));
        $expected->setSymbol($formData['symbol']);
        $expected->setType($formData['type']);
        $expected->setVolume($formData['volume']);
        $expected->setPriceLevel($formData['priceLevel']);
        $expected->setStopLoss($formData['stopLoss']);
        $expected->setCommission($formData['commission']);
        $expected->setDividend($formData['dividend']);
        $expected->setSwap($formData['swap']);
        $expected->setProfit($formData['profit']);
        $expected->setSystem($formData['system']);
        $expected->setStrategy($formData['strategy']);
        $expected->setAssetClass($formData['assetClass']);
        $expected->setGrade($formData['grade']);
        $expected->setState($formData['state']);

        //Act
        $form->submit($formData);

        // Assert
        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
        $this->assertEquals($expected->getTime(), $model->getTime());
        $this->assertEquals($formData['time'], $model->getTime()->format('Y.m.d H:i:s'));

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testSubmitInvalidData() //It doesn't pass the test
    {
        //Arrange
        $formData = [
            'time' => 'invalid-date-format',
            'type' => '',
            'volume' => 'invalid-number',
            'priceLevel' => 'invalid-number',
            'stopLoss' => 'invalid-number',
            'commission' => 'invalid-number',
            'dividend' => 'invalid-number',
            'swap' => 'invalid-number',
            'profit' => 'invalid-number',
            'system' => '',
            'strategy' => '',
            'assetClass' => '',
            'grade' => 'invalid-grade',
            'state' => 'invalid-state',
        ];

        $model = new PositionState();
        $form = $this->factory->create(PositionStateType::class, $model);

        //Act
        $form->submit($formData);

        // Assert
        $this->assertFalse($form->isValid());
    }
}