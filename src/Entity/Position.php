<?php

namespace App\Entity;

use App\DTO\PositionDTO;
use App\Factory\PositionDTOFactory;
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
        $lastState = null;

        foreach ($this->getPositionStates($this) as $state) {
            if ($lastState === null || $state->getTime() > $lastState->getTime()) {
                $lastState = $state;
            }
        }

        return $lastState;
    }

    public function getInitialState(): PositionState
    {
        foreach ($this->getPositionStates($this) as $state){
            if ($state->getState() === PositionState::STATE_OPENED){
                $initialState = $state;
            }
        }
        return $initialState;
    }

    public function getEntryLevel(): ?float
    {
        $initialEntry = $this->getInitialState()->getPriceLevel();
        $initialEntryVolumeAdjusted = $initialEntry * $this->getInitialState()->getVolume();

            foreach ($this->getPositionStates($this) as $state){

                if($state->getState() === PositionState::STATE_SCALE_IN){
                    $scaleInEntry = ($state->getPriceLevel() * $state->getVolume());

                    $entry = $initialEntryVolumeAdjusted + $scaleInEntry;
                    $volume = $this->getInitialState()->getVolume() + $state->getVolume();

                    $entryLevel = $entry / $volume;


                    return $this->formatLevel($entryLevel, $state);
               }
            }

        return $this->formatLevel($initialEntry, $this->getInitialState());
    }

    public function getEntryTime(): ?\DateTimeImmutable
    {
        $lastState = $this->getLastState();

        if ($lastState){
            if ($lastState->getState() === PositionState::STATE_OPENED ||
            $lastState->getState() === PositionState::STATE_PARTIALLY_CLOSED ||
            $lastState->getState() === PositionState::STATE_SCALE_IN) {
            return $lastState->getTime();}
        }

        return null;
    }

    public function getExitLevel():?float
    {
        $lastState = $this->getLastState();

            if ($lastState->getState() === PositionState::STATE_CLOSED){

                $lastExit = $lastState->getPriceLevel() * $lastState->getVolume();

                foreach ( $this->getPositionStates($this) as $state){

                    if ($state->getState() === PositionState::STATE_PARTIALLY_CLOSED){
                        $currentExit = $state->getPriceLevel() * $state->getVolume();
                        $volume = $this->getClosedPositionVolume();
                        $combinedExit = $lastExit + $currentExit;
                        $exitLevel = $combinedExit / $volume;

                            return $this->formatLevel($exitLevel, $state);
                        }
                    }
                }

        return $this->formatLevel($lastState->getPriceLevel(), $lastState);
    }
    public function getClosedPositionVolume(): float
    {
        $lastState = $this->getLastState();
        if ($lastState->getState() === PositionState::STATE_CLOSED){
            $volume = 0;
            foreach ( $this->getPositionStates($this) as $state){
                if($state->getState() === PositionState::STATE_OPENED || $state->getState() === PositionState::STATE_SCALE_IN){
                    $volume += $state->getVolume();
                }
            }
        }
        return $volume;
    }

    public function getOpenPositionVolume():float
    {
        $volume = $this->getInitialState()->getVolume();

        if ($this->getLastState()->getState() === PositionState::STATE_SCALE_IN || $this->getLastState()->getState() === PositionState::STATE_PARTIALLY_CLOSED){

            foreach ($this->getPositionStates($this) as $state){
                if($state->getState() === PositionState::STATE_SCALE_IN){
                    $volume += $state->getVolume();
                }
                if ($state->getState() === PositionState::STATE_PARTIALLY_CLOSED){
                    $volume -= $state->getVolume();
                }
            }
        }

        return $volume;
    }

    public function calculateProfit(): float
    {
        $profit = 0;
        foreach($this->getPositionStates($this) as $state){
            $profit += $state->getProfit();
        }

        return $profit;
    }

    public function calculateSwap(): float
    {
        $swap = 0;
        foreach($this->getPositionStates($this) as $state){
            $swap += $state->getSwap();
        }

        return $swap;
    }

    public function calculateCommission(): float
    {
        $commission = 0;
        foreach($this->getPositionStates($this) as $state){
            $commission += $state->getCommission();
        }

        return $commission;
    }

    private function formatLevel(float $level, PositionState $state): float
    {
        if ($state->getAssetClass() === 'currencies') {
            if (str_contains($state->getSymbol(), 'JPY')) {
                return round($level, 2);
            }

            return round($level, 4);

        }

        return round($level, 2);
    }
}
