<?php declare(strict_types=1);

namespace App\DTO;

class PositionDTO
{
    private int $positionId;
    private float $entryLevel;
    private \DateTimeImmutable $entryTime;
    private string $symbol;
    private string $type;
    private float $volume;
    private float $stopLoss;
    private float $commission;
    private ?\DateTimeImmutable $exitTime;
    private ?float $exit;
    private float $dividend;
    private float $swap;
    private float $profit;
    private string $system;
    private string $strategy;
    private string $assetClass;
    private string $grade;
    private string $state;

    public function __construct(
        int $positionId,
        float $entryLevel,
        \DateTimeImmutable $entryTime,
        string $symbol,
        string $type,
        float $volume,
        float $stopLoss,
        float $commission,
        ?\DateTimeImmutable $exitTime,
        ?float $exitLevel,
        float $dividend,
        float $swap,
        float $profit,
        string $system,
        string $strategy,
        string $assetClass,
        string $grade,
        string $state
    )
    {
        $this->positionId = $positionId;
        $this->entryLevel = $entryLevel;
        $this->entryTime = $entryTime;
        $this->symbol = $symbol;
        $this->type = $type;
        $this->volume = $volume;
        $this->stopLoss = $stopLoss;
        $this->commission = $commission;
        $this->exitTime = $exitTime;
        $this->exit = $exitLevel;
        $this->dividend = $dividend;
        $this->swap = $swap;
        $this->profit = $profit;
        $this->system = $system;
        $this->strategy = $strategy;
        $this->assetClass = $assetClass;
        $this->grade = $grade;
        $this->state = $state;

    }

    public function getPositionId(): int
    {
        return $this->positionId;
    }

    public function getEntryLevel(): float
    {
        return $this->entryLevel;
    }

    public function getEntryTime(): \DateTimeImmutable
    {
        return $this->entryTime;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVolume(): float
    {
        return $this->volume;
    }

    public function getStopLoss(): float
    {
        return $this->stopLoss;
    }

    public function getCommission(): float
    {
        return $this->commission;
    }

    public function getExitTime(): ?\DateTimeImmutable
    {
        return $this->exitTime;
    }

    public function getExitLevel(): ?float
    {
        return $this->exit;
    }

    public function getDividend(): float
    {
        return $this->dividend;
    }

    public function getSwap(): float
    {
        return $this->swap;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function getSystem(): string
    {
        return $this->system;
    }

    public function getStrategy(): string
    {
        return $this->strategy;
    }

    public function getAssetClass(): string
    {
        return $this->assetClass;
    }

    public function getGrade(): string
    {
        return $this->grade;
    }

    public function getState(): string
    {
        return $this->state;
    }



}