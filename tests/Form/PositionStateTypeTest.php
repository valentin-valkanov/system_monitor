<?php declare(strict_types=1);

namespace App\Tests\Form;

use App\Entity\PositionState;
use App\Form\PositionStateType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class PositionStateTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $formType = new PositionStateType();
        return [
            new PreloadedExtension([$formType], []),
        ];
    }

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
            'time' => 'invalid-date-format',  // Invalid date format
            'symbol' => '',                   // Empty string, invalid if required
            'type' => '',                     // Empty string, invalid if required
            'volume' => 'invalid-number',     // Invalid number, should be a float
            'priceLevel' => 'invalid-number', // Invalid number, should be a float
            'stopLoss' => 'invalid-number',   // Invalid number, should be a float
            'commission' => 'invalid-number', // Invalid number, should be a float
            'dividend' => 'invalid-number',   // Invalid number, should be a float
            'swap' => 'invalid-number',       // Invalid number, should be a float
            'profit' => 'invalid-number',     // Invalid number, should be a float
            'system' => '',                   // Empty string, invalid if required
            'strategy' => '',                 // Empty string, invalid if required
            'assetClass' => '',               // Empty string, invalid if required
            'grade' => 'invalid-grade',       // Invalid choice, not in allowed values
            'state' => 'invalid-state',       // Invalid choice, not in allowed values
        ];

        $model = new PositionState();
        $form = $this->factory->create(PositionStateType::class, $model);

        //Act
        $form->submit($formData);

        // Assert
        $this->assertFalse($form->isValid());

        $errors = $form->getErrors(true);
        // Iterate over errors and print them
        foreach ($errors as $error) {
            $field = $error->getOrigin()->getName();
            echo "Field: $field - Error: " . $error->getMessage() . "\n";
        }

        $this->assertCount(15, $errors);

    }
}