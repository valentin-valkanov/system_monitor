<?php

namespace App\Entity;

use App\DTO\ClosedPositionDTO;
use App\DTO\PositionDTO;
use App\Repository\PositionRepository;
use App\Utils\DateUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'position', targetEntity: PositionState::class, orphanRemoval: true)]
    private Collection $positionStates;

    public function __construct()
    {
        $this->positionStates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPositionStates(Position $position): Collection
    {
        return $this->positionStates;
    }

    public function addPositionState(PositionState $positionState): self
    {
        if (!$this->positionStates->contains($positionState)) {
            $this->positionStates[] = $positionState;
            $positionState->setPosition($this);
        }

        return $this;
    }

    public function getLastState(): PositionState
    {
        $lastState = $this->positionStates->last();
        return $lastState;
    }

    public function getInitialState()
    {
        $initialState = $this->positionStates->first();
        return $initialState;
    }

    public function getEntryLevel(): ?float
    {
        $lastState = $this->getLastState();

        if ($lastState) {
            if ($lastState->getState() === PositionState::STATE_OPENED ||
                $lastState->getState() === PositionState::STATE_PARTIALLY_CLOSED ||
                $lastState->getState() === PositionState::STATE_SCALE_IN) {
                return $lastState->getPriceLevel();
            }
        }

        return null;
    }

    public function getExitLevel(): ?float
    {
        $lastState = $this->getLastState();
        $exitLevel = null;
        $totalPrice = 0;
        $count = 0;

        if ($lastState) {
            if ($lastState->getState() === PositionState::STATE_CLOSED) {
                $exitLevel = $lastState->getPriceLevel();
            } elseif ($lastState->getState() === PositionState::STATE_PARTIALLY_CLOSED) {
                $totalPrice += $lastState->getPriceLevel();
                $count++;
            }
        }

        return $exitLevel ?? ($count > 0 ? $totalPrice / $count : null);
    }

    public function getEntryTime(): ?\DateTimeImmutable
    {
        $lastState = $this->getLastState();

        if($lastState){
            if ($lastState->getState() === PositionState::STATE_OPENED ||
            $lastState->getState() === PositionState::STATE_PARTIALLY_CLOSED ||
            $lastState->getState() === PositionState::STATE_SCALE_IN) {
            return $lastState->getTime();}
        }
        return null;
    }

    public function addFieldsToClosedPositions(): PositionDTO
    {
        $state = $this->getLastState();

        $entryLevel = $this->getInitialState()->getPriceLevel();
        $entryTime = $this->getInitialState()->getTime();
        $exitLevel = $this->getLastState()->getPriceLevel();
        $exitTime = $this->getLastState()->getTime();

        $positionDTO = new PositionDTO(
            $this->getId(),
            $entryLevel,
            $entryTime,
            $state->getSymbol(),
            $state->getType(),
            $state->getVolume(),
            $state->getStopLoss(),
            $state->getCommission(),
            $exitTime,
            $exitLevel,
            $state->getDividend(),
            $state->getSwap(),
            $state->getProfit(),
            $state->getSystem(),
            $state->getStrategy(),
            $state->getAssetClass(),
            $state->getGrade(),
            $state->getState()
        );

        return $positionDTO;
    }

    public function printClosedPositionsForCurrentWeek(): array
    {
        $closedPositions = [];
        [$startOfWeek, $endOfWeek] = DateUtils::getCurrentWeekRange();

        foreach ($this->positionStates as $state) {
            if ($state->getState() === PositionState::STATE_CLOSED &&
                $state->getTime() >= $startOfWeek && $state->getTime() <= $endOfWeek) {

                $entryLevel = $this->getEntryLevel();
                $exitLevel = $this->getExitLevel();
                $entryTime = $this->getEntryTime();
                $exitTime = $state->getTime();

                $closedPositions[] = new ClosedPositionDTO(
                    $this->getId(),
                    $entryLevel,
                    $exitLevel,
                    $entryTime,
                    $exitTime,
                );
            }
        }
        return $closedPositions;
    }

    public function addFieldsToOpenPositions(): PositionDTO
    {
        $state = $this->getLastState();

        $entryLevel = $this->getEntryLevel();
        $entryTime = $this->getEntryTime();
        $exitLevel = null;
        $exitTime = null;

        $openPositionDTO = new PositionDTO(
            $this->getId(),
            $entryLevel,
            $entryTime,
            $state->getSymbol(),
            $state->getType(),
            $state->getVolume(),
            $state->getStopLoss(),
            $state->getCommission(),
            $exitTime,
            $exitLevel,
            $state->getDividend(),
            $state->getSwap(),
            $state->getProfit(),
            $state->getSystem(),
            $state->getStrategy(),
            $state->getAssetClass(),
            $state->getGrade(),
            $state->getState()
        );

        return $openPositionDTO;
    }
}
