<?php


namespace Tests\Game\Battleship;

use Game\Battleship\GameUnit;
use Game\Battleship\Location;
use Game\Battleship\PlayerEmulator;
use Game\Battleship\ShipLocation;
use PHPUnit\Framework\Assert;


class NotRandomPlayerEmulator extends PlayerEmulator {

    private $locations;

    private $shipLocations;

    private $allowOriginalRandomCalculation;

    public function __construct(GameUnit $gameUnit) {
        parent::__construct($gameUnit);
        $this->allowOriginalRandomCalculation = true;
    }

    public function setDesiredLocations($locations) {
        $this->locations = $locations;
    }

    public function setDesiredShipLocations($shipLocations) {
        $this->shipLocations = $shipLocations;
    }

    public function disableOriginalRandomCalculations() {
        $this->allowOriginalRandomCalculation = false;
    }

    protected function getRandomShipLocation() : ShipLocation {
        if ($this->allowOriginalRandomCalculation) {
            return parent::getRandomShipLocation();
        }
        if (sizeof($this->shipLocations) === 0) {
            Assert::fail('A configured ShipLocation is missing');
        }
        $shipLocation = $this->shipLocations[0];
        unset($this->shipLocations[0]);
        $this->shipLocations = array_values($this->shipLocations);
        return $shipLocation;
    }

    protected function getRandomLocation(): Location {
        if ($this->allowOriginalRandomCalculation) {
            return parent::getRandomLocation();
        }
        if (sizeof($this->locations) === 0) {
            Assert::fail('A configured Location is missing');
        }
        $location = $this->locations[0];
        unset($this->locations[0]);
        $this->locations = array_values($this->locations);
        return $location;
    }

    protected function getMaxTries() {
        if ($this->allowOriginalRandomCalculation) {
            return parent::getMaxTries();
        }
        return 1;
    }

    public function verifyShot(Location $location) {
        return $this->getGameUnit()->isTargetLocationMarked($location);
    }
}