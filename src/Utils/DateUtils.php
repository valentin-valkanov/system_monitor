<?php declare(strict_types=1);

namespace App\Utils;
class DateUtils
{
    public static function getCurrentWeekRange(): array
    {
        $startOfWeek = new \DateTime();
        $startOfWeek->setISODate((int)date('Y'), (int)date('W'));
        $startOfWeek->setTime(0, 0, 0);

        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');
        $endOfWeek->setTime(23, 59, 59);

        return [$startOfWeek, $endOfWeek];
    }
}