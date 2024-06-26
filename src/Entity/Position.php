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
        $lastState = $this->positionStates->last();
        return $lastState;
    }

    public function getInitialState(): PositionState
    {
        foreach ($this->getPositionStates($this) as $state){
            if($state->getState() === PositionState::STATE_OPENED){
                $initialState = $state;
            }
        }
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

    public function getExitLevel():?float //relevant only STATE_CLOSED and STATE_PARTIALLY_CLOSED
    {
        $lastState = $this->getLastState();
        $currentExitLevel = 0;

        if ($lastState) {
            if ($lastState->getState() === PositionState::STATE_CLOSED){
                foreach ( $this->getPositionStates($this) as $state){
                    if($state->getState() === PositionState::STATE_PARTIALLY_CLOSED){
                        $currentExitLevel += ($state->getPriceLevel() * $state->getVolume());
                    }
                }
                $currentExitLevel += $lastState->getPriceLevel() * $lastState->getVolume();
            }
        }
        $exitLevel = round($currentExitLevel / $this->getInitialState()->getVolume(), 4);
        return $exitLevel;
    }

    public function getCombinedVolume():float //relevant to STATE_SCALE_IN
    {
        $volume = $this->getInitialState()->getVolume();

        foreach ($this->getPositionStates($this) as $state){

            if($state->getState() === PositionState::STATE_SCALE_IN){

                $volume += $state->getVolume();
            }
        }
        return $volume;
    }
}
