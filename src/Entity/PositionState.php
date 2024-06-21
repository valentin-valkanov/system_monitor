<?php

namespace App\Entity;

use App\Form\PositionStateType;
use App\Repository\PositionStateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionStateRepository::class)]
class PositionState
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $time = null;

    #[ORM\Column(length: 255)]
    private ?string $symbol = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $volume = null;

    #[ORM\Column]
    private ?float $priceLevel = null;

    #[ORM\Column]
    private ?float $stopLoss = null;

    #[ORM\Column]
    private ?float $commission = null;

    #[ORM\Column]
    private ?float $dividend = null;

    #[ORM\Column]
    private ?float $swap = null;

    #[ORM\Column]
    private ?float $profit = null;

    #[ORM\Column(length: 255)]
    private ?string $system = null;

    #[ORM\Column(length: 255)]
    private ?string $strategy = null;

    #[ORM\Column(length: 255)]
    private ?string $assetClass = null;

    #[ORM\Column(length: 255)]
    private ?string $grade = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;
    #[ORM\ManyToOne(targetEntity: Position::class, inversedBy: 'positionStates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Position $position = null;

    public const STATE_OPENED = 'opened';
    public const STATE_PARTIALLY_CLOSED = 'partially_closed';
    public const STATE_SCALE_IN = 'scale_in';
    public const STATE_CLOSED = 'closed';

    public const GRADE_NONE = 'none';
    public const GRADE_A = 'A';
    public const GRADE_B = 'B';
    public const GRADE_C = 'C';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?\DateTimeImmutable
    {
        return $this->time;
    }

    public function setTime(?\DateTimeImmutable $time): void
    {
        $this->time = $time;
    }


    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(float $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getPriceLevel(): ?float
    {
        return $this->priceLevel;
    }

    public function setPriceLevel(?float $priceLevel): void
    {
        $this->priceLevel = $priceLevel;
    }


    public function getStopLoss(): ?float
    {
        return $this->stopLoss;
    }

    public function setStopLoss(float $stopLoss): static
    {
        $this->stopLoss = $stopLoss;

        return $this;
    }

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): static
    {
        $this->commission = $commission;

        return $this;
    }
    public function getDividend(): ?float
    {
        return $this->dividend;
    }

    public function setDividend(float $dividend): static
    {
        $this->dividend = $dividend;

        return $this;
    }

    public function getSwap(): ?float
    {
        return $this->swap;
    }

    public function setSwap(float $swap): static
    {
        $this->swap = $swap;

        return $this;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): static
    {
        $this->profit = $profit;

        return $this;
    }

    public function getSystem(): ?string
    {
        return $this->system;
    }

    public function setSystem(string $system): static
    {
        $this->system = $system;

        return $this;
    }

    public function getStrategy(): ?string
    {
        return $this->strategy;
    }

    public function setStrategy(string $strategy): static
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getAssetClass(): ?string
    {
        return $this->assetClass;
    }

    public function setAssetClass(string $assetClass): static
    {
        $this->assetClass = $assetClass;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): void
    {
        $this->state = $state;
    }


    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): static
    {
        $this->position = $position;

        return $this;
    }
}
