<?php declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use App\Utils\DateUtils;
use PHPUnit\Framework\TestCase;

class DateUtilsTest extends TestCase
{
    public function testGetCurrentWeekRange()
    {
        // Arrange: get the current week range from the method
        [$startOfWeek, $endOfWeek] = DateUtils::getCurrentWeekRange();

        // Act: Calculate the expected start and end of the week
        $expectedStartOfWeek = new \DateTime();
        $expectedStartOfWeek->setISODate((int)date('Y'), (int)date('W'));
        $expectedStartOfWeek->setTime(0, 0, 0);

        $expectedEndOfWeek = clone $expectedStartOfWeek;
        $expectedEndOfWeek->modify('+6 days');
        $expectedEndOfWeek->setTime(23, 59, 59);

        // Assert
        $this->assertEquals($expectedStartOfWeek->format('Y-m-d H:i:s'), $startOfWeek->format('Y-m-d H:i:s'));
        $this->assertEquals($expectedEndOfWeek->format('Y-m-d H:i:s'), $endOfWeek->format('Y-m-d H:i:s'));
    }
}