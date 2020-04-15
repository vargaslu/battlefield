<?php


namespace Game\Battleship;

require_once __DIR__ . '/../positioning/Direction.php';
require_once __DIR__ . '/../positioning/LocationUtils.php';

class ShipsNextLocationCalculator {

    private $shipsLocationQueue;

    private $shipsLives;

    private $currentIndex;

    public function __construct() {
        $this->shipsLocationQueue = [];
        $this->shipsLives = [];
    }

    public function createCalculations($shipName, Location $foundLocation, $size) {
        if (!array_key_exists($shipName, $this->shipsLocationQueue)) {
            $this->shipsLives[$shipName] = $size;
            $this->shipsLocationQueue[$shipName] = [$foundLocation];
            $this->currentIndex = 1;
            $this->fillArrayWithAroundLocations($foundLocation);
        }
    }

    private function fillArrayWithAroundLocations(Location $location) {
        $verticalUpLocation = LocationUtils::decrease($location, Direction::VERTICAL);
        $firstShipValues = &$this->getFirstShipValues();
        array_push($firstShipValues, $verticalUpLocation);

        $horizontalLeftLocation = LocationUtils::decrease($location, Direction::HORIZONTAL);
        $firstShipValues = &$this->getFirstShipValues();
        array_push($firstShipValues, $horizontalLeftLocation);

        $verticalDownLocation = LocationUtils::increase($location, Direction::VERTICAL);
        $firstShipValues = &$this->getFirstShipValues();
        array_push($firstShipValues, $verticalDownLocation);

        $horizontalRightLocation = LocationUtils::increase($location, Direction::HORIZONTAL);
        $firstShipValues = &$this->getFirstShipValues();
        array_push($firstShipValues, $horizontalRightLocation);
    }

    public function markHitToShip($shipName) {
        $this->shipsLives[$shipName]--;
    }

    public function recalculateNextPossibleLocations() {

    }

    public function getCurrentSize() : int {
        if (sizeof($this->shipsLocationQueue) === 0) {
            return -1;
        }
        $firstShipName = array_keys($this->shipsLocationQueue)[0];
        return $this->shipsLives[$firstShipName];
    }

    public function __toString() {
        if (sizeof($this->shipsLocationQueue) === 0) {
            return '[]';
        }

        $firstShipValues = $this->getFirstShipValues();
        $resultString = '[ ';
        foreach ($firstShipValues as $locationValue) {
            $resultString .= (string) $locationValue.', ';
        }
        $resultString = rtrim($resultString, ', ');
        $resultString .= ' ]';
        return $resultString;
    }

    private function &getFirstShipValues() : array {
        $firstShipName = array_keys($this->shipsLocationQueue)[0];
        return $this->shipsLocationQueue[$firstShipName];
    }
}