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

    public function getPositionStates(): Collection
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

        foreach ($this->getPositionStates() as $state) {
            if ($lastState === null || $state->getTime() > $lastState->getTime()) {
                $lastState = $state;
            }
        }

        return $lastState;
    }

    public function getInitialState(): PositionState
    {
        $initialState = null;

        foreach ($this->getPositionStates() as $state) {
            if ($initialState === null || $state->getTime() < $initialState->getTime()) {
                $initialState = $state;
            }
        }

        return $initialState;
    }

    public function getEntryTime(): ?\DateTimeImmutable
    {
        $entryTime = $this->getInitialState()->getTime();

        return $entryTime;
    }

    public function getEntryLevel(): ?float
    {
        $combinedEntryLevel = 0;
        $combinedVolume = 0;
        foreach ($this->getPositionStates() as $state){

            if($state->getState() === PositionState::STATE_OPENED || $state->getState() === PositionState::STATE_SCALE_IN) {
                $currentEntryLevel = $state->getPriceLevel() * $state->getVolume();
                $combinedEntryLevel += $currentEntryLevel;
                $combinedVolume += $state->getVolume();
            }

        }
        $exitLevel = $combinedEntryLevel / $combinedVolume;
        return $this->formatLevel($exitLevel, $state);
    }
    public function getExitLevel():?float
    {
        $combinedExitLevel = 0;
        $combinedVolume = 0;

        foreach ( $this->getPositionStates() as $state){

            if ($state->getState() === PositionState::STATE_PARTIALLY_CLOSED || $state->getState() === PositionState::STATE_CLOSED){
                $currentExitLevel = $state->getPriceLevel() * $state->getVolume();
                $combinedExitLevel += $currentExitLevel;
                $combinedVolume += $state->getVolume();
            }
        }

        $exitLevel = $combinedExitLevel / $combinedVolume;
        return $this->formatLevel($exitLevel, $state);
    }
    public function getClosedPositionVolume(): float
    {
        $lastState = $this->getLastState();

        if ($lastState->getState() === PositionState::STATE_CLOSED){
            $volume = 0;
            foreach ( $this->getPositionStates() as $state){
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

            foreach ($this->getPositionStates() as $state){
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
        foreach($this->getPositionStates() as $state){
            $profit += $state->getProfit();
            if($state->getState() == 'closed'){
                break;
            }
        }

        return $profit;
    }

    public function calculateSwap(): float
    {
        $swap = 0;
        foreach($this->getPositionStates() as $state){
            $swap += $state->getSwap();
            if($state->getState() == 'closed'){
                break;
            }
        }

        return $swap;
    }

    public function calculateCommission(): float
    {
        $commission = 0;
        foreach($this->getPositionStates() as $state){
            $commission += $state->getCommission();
            if($state->getState() == 'closed'){
                break;
            }
        }

        return $commission;
    }

    public function calculateStopLevel()
    {
        $stopLevel = 0;
        if($this->getLastState()== 'closed'){
            $stopLevel = $this->getInitialState()->getStopLoss();
            return $stopLevel;
        }
        $stopLevel = $this->getLastState()->getStopLoss();
        return $stopLevel;
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
