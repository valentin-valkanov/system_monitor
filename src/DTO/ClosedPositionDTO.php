<?php declare(strict_types=1);

namespace App\DTO;

class ClosedPositionDTO
{
    private int $positionId;
    private float $entryLevel;
    private float $exitLevel;
    private \DateTimeImmutable $exitTime;
    private \DateTimeImmutable $entryTime;

    public function __construct(
        int $positionId,
        float $entryLevel,
        float $exitLevel,
        \DateTimeImmutable $exitTime,
        \DateTimeImmutable $entryTime
    )
    {
        $this->positionId = $positionId;
        $this->entryLevel = $entryLevel;
        $this->exitLevel = $exitLevel;
        $this->exitTime = $exitTime;
        $this->entryTime = $entryTime;
    }

    // Getters for each property
    public function getPositionId(): int
    {
        return $this->positionId;
    }

    public function getEntryLevel(): float
    {
        return $this->entryLevel;
    }

    public function getExitLevel(): float
    {
        return $this->exitLevel;
    }

    public function getExitTime(): \DateTimeImmutable
    {
        return $this->exitTime;
    }

    public function getEntryTime(): \DateTimeImmutable
    {
        return $this->entryTime;
    }
}