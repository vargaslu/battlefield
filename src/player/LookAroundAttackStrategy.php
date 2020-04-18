<?php


namespace Game\Battleship;

require_once 'AttackStrategy.php';
require_once 'ShipsNextLocationCalculator.php';

class LookAroundAttackStrategy implements AttackStrategy {

    private $gameUnit;

    private $shipsNextLocationCalculator;

    public function __construct(GameUnit $gameUnit) {
        $this->gameUnit = $gameUnit;
        $this->shipsNextLocationCalculator = new ShipsNextLocationCalculator($gameUnit);
    }

    function makeShot(): void {
        $location = $this->getNextShotLocationIfAvailable();
        $hitResult = $this->gameUnit->callShotIntoLocation($location);
        $this->calculateNextPossibleLocation($hitResult, $location);
        error_log('Made shot: ' . (string) $location . ' hit: '. (string) $hitResult->isHit());
    }

    private function getNextShotLocationIfAvailable() {
        if ($this->shipsNextLocationCalculator->getNumberOfStoredShips() === 0) {
            return $this->getRandomLocation();
        }

        return $this->shipsNextLocationCalculator->getCurrentLocation();
    }

    private function calculateNextPossibleLocation(HitResult $hitResult, Location $hitLocation) {
        if ($hitResult->isHit()) {
            $this->runSuccessFulHitActions($hitResult, $hitLocation);
        } else {
            $this->shipsNextLocationCalculator->removeCurrentLocation();
        }
    }

    private function runSuccessFulHitActions(HitResult $hitResult, Location $hitLocation): void {
        $shipName = $hitResult->getShipName();
        if (!$this->shipsNextLocationCalculator->existsInQueue($shipName)) {
            $shipSize = ShipFactory::getSize($shipName);
            $this->shipsNextLocationCalculator->createCalculations($shipName, $hitLocation, $shipSize);
        } else {
            $this->shipsNextLocationCalculator->hitShip($shipName);
        }
    }

    protected function getRandomLocation() {
        $availableTargetPositions = $this->gameUnit->getFreeAvailableTargetPositions();
        $index = Utils::getRandomNumberFromTo(0, sizeof($availableTargetPositions) - 1);
        $letter = $availableTargetPositions[$index][0];
        $column = $availableTargetPositions[$index][1];
        return new Location($letter, $column);
    }

    protected function getGameUnit(): GameUnit {
        return $this->gameUnit;
    }
}