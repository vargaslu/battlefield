<?php


namespace Game\Battleship;

require_once __DIR__ . '/../positioning/Direction.php';
require_once __DIR__ . '/../positioning/LocationUtils.php';

class ShipsNextLocationCalculator {

    private const HORIZONTAL_RIGHT = 1;

    private const HORIZONTAL_LEFT = -1;

    private const VERTICAL_UP = -2;

    private const VERTICAL_DOWN = 2;

    private $shipsLocationQueue;

    private $shipsLives;

    private $currentIndex;

    private $gameUnit;

    public function __construct(GameUnit $gameUnit) {
        $this->gameUnit = $gameUnit;
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
        $this->tryAddDecreasedLocationForDirection($location, Direction::VERTICAL);

        $this->tryAddDecreasedLocationForDirection($location, Direction::HORIZONTAL);

        $this->tryAddIncreasedLocationForDirection($location, Direction::VERTICAL);

        $this->tryAddIncreasedLocationForDirection($location, Direction::HORIZONTAL);
    }

    private function tryAddDecreasedLocationForDirection(Location $location, $direction, $times = 1, $reThrowException = false) {
        try {
            $newLocation = $location;
            for ($i = 0; $i < $times; $i++) {
                $newLocation = LocationUtils::decrease($newLocation, $direction);
                $this->addWhenTargetLocationsIsFree($newLocation);
            }
        } catch (InvalidLocationException $exception) {
            if ($reThrowException) {
                throw $exception;
            }
        }
    }

    private function tryAddIncreasedLocationForDirection(Location $location, $direction, $times = 1, $reThrowException = false) {
        try {
            $newLocation = $location;
            for ($i = 0; $i < $times; $i++) {
                $newLocation = LocationUtils::increase($newLocation, $direction);
                $this->addWhenTargetLocationsIsFree($newLocation);
            }
        } catch (InvalidLocationException $exception) {
            if ($reThrowException) {
                throw $exception;
            }
        }
    }

    private function addWhenTargetLocationsIsFree(Location $newLocation) {
        if ($this->gameUnit->isTargetLocationMarked($newLocation)) {
            throw new InvalidLocationException();
        }
        $firstShipValues = &$this->getFirstShipValues();
        array_push($firstShipValues, $newLocation);
    }

    public function markHitToShip($shipName) {
        $this->shipsLives[$shipName]--;
    }

    public function recalculateNextPossibleLocations() {
        $startingLocation= $this->getFirstLocation();
        $currentLocation = $this->getCurrentLocation();
        $direction = $this->getNextLocationsDirection($startingLocation, $currentLocation);
        $firstShipValues = &$this->getFirstShipValues();

        array_splice($firstShipValues, $this->currentIndex + 1);
        try {
            $currentLocation = $this->getCurrentLocation();
            $this->fillWithNextLocationsInTheCorrectDirection($currentLocation, $direction);
        } catch (InvalidLocationException $exception) {
            $turnAroundDirection = $this->getTurnAroundDirection($direction);
            $firstLocation = $this->getFirstLocation();
            $this->fillWithNextLocationsInTheCorrectDirection($firstLocation, $turnAroundDirection);
        }
    }

    private function getTurnAroundDirection($direction) {
        switch ($direction) {
            case self::HORIZONTAL_RIGHT:
                return self::HORIZONTAL_LEFT;
            case self::HORIZONTAL_LEFT:
                return self::HORIZONTAL_RIGHT;
            case self::VERTICAL_UP:
                return self::VERTICAL_DOWN;
            case self::VERTICAL_DOWN:
                return self::VERTICAL_UP;
        }
    }

    private function fillWithNextLocationsInTheCorrectDirection(Location $fromLocation, $direction) {
        $times = $this->getCurrentSize();
        if (self::HORIZONTAL_LEFT === $direction) {
            $this->tryAddDecreasedLocationForDirection($fromLocation, Direction::HORIZONTAL, $times, true);
        } elseif (self::HORIZONTAL_RIGHT === $direction) {
            $this->tryAddIncreasedLocationForDirection($fromLocation, Direction::HORIZONTAL, $times, true);
        } elseif (self::VERTICAL_UP === $direction) {
            $this->tryAddDecreasedLocationForDirection($fromLocation, Direction::VERTICAL, $times, true);
        } elseif (self::VERTICAL_DOWN === $direction) {
            $this->tryAddIncreasedLocationForDirection($fromLocation, Direction::VERTICAL, $times, true);
        }
    }

    public function removeCurrentLocation() {
        $firstShipValues = &$this->getFirstShipValues();
        unset($firstShipValues[$this->currentIndex]);
        $firstShipValues = array_values($firstShipValues);
    }

    private function getFirstLocation() : Location {
        $firstShipValues = $this->getFirstShipValues();
        return $firstShipValues[0];
    }

    public function getCurrentLocation() : Location {
        $firstShipValues = $this->getFirstShipValues();
        return $firstShipValues[$this->currentIndex];
    }

    private function getNextLocationsDirection(Location $firstLocation, Location $secondLocation) : int {
        $firstColumn = $firstLocation->getColumn();
        $firstLetter = ord($firstLocation->getLetter());
        $secondColumn = $secondLocation->getColumn();
        $secondLetter = ord($secondLocation->getLetter());
        if ($firstLocation->getColumn() == $secondLocation->getColumn()) {
            return ($firstLetter - $secondLetter) > 0 ? self::VERTICAL_UP : self::VERTICAL_DOWN;
        } elseif ($firstLocation->getLetter() == $secondLocation->getLetter()) {
            return ($firstColumn - $secondColumn) > 0 ? self::HORIZONTAL_LEFT : self::HORIZONTAL_RIGHT;
        } else {
            throw new InvalidLocationException('Invalid locations to verify: '
                                               .(string)$firstLocation.' '.(string)$secondLocation);
        }
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