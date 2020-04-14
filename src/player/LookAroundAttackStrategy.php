<?php


namespace Game\Battleship;

require_once 'AttackStrategy.php';

class LookAroundAttackStrategy implements AttackStrategy {

    private $gameUnit;

    private $successfulHitLocation;

    private $successfulHitShip;

    private $successfulHitShipLocation;

    public function __construct(GameUnit $gameUnit) {
        $this->gameUnit = $gameUnit;
        $this->successfulHitShipLocation = [];
    }

    function makeShot(): void {
        $location = $this->calculateNextPossibleLocation();
        $hitResult = $this->gameUnit->makeShot($location);
        $this->keepLocationOnSuccessfulHit($hitResult, $location);
    }

    private function calculateNextPossibleLocation() : Location {
        if (isset($this->successfulHitShipLocation)) {
            $this->calculateAroundFirstSuccessfulShot();
            return $this->getRandomLocation();
        } else {
            return $this->getRandomLocation();
        }
    }

    private function calculateAroundFirstSuccessfulShot() {
        reset($this->successfulHitShipLocation);
        $shipName = key($this->successfulHitShipLocation);
    }

    private function keepLocationOnSuccessfulHit(HitResult $hitResult, Location $location): void {
        if (!$hitResult->isHit()) {
            return;
        }

        if (!isset($this->successfulHitShipLocation[$hitResult->getShipName()])) {
            $this->successfulHitShipLocation[$hitResult->getShipName()] = [];
        }
        array_push($this->successfulHitShipLocation[$hitResult->getShipName()], $location);
    }

    protected function getRandomLocation() {
        return Utils::getRandomLocation();
    }

    protected function getGameUnit(): GameUnit {
        return $this->gameUnit;
    }
}