<?php


namespace Tests\Game\Battleship;

use Game\Battleship\GameUnit;
use Game\Battleship\Location;
use Game\Battleship\PlayerEmulator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;


class NotRandomPlayerEmulator extends PlayerEmulator {

    private $locations;

    private $directions;

    private $allowOriginalRandomCalculation;

    public function __construct(GameUnit $gameUnit) {
        parent::__construct($gameUnit);
        $this->allowOriginalRandomCalculation = true;
    }

    public function setDesiredLocations($locations) {
        $this->locations = $locations;
    }

    public function setDesiredDirections($directions) {
        $this->directions = $directions;
    }

    public function disableOriginalRandomCalculations() {
        $this->allowOriginalRandomCalculation = false;
    }

    protected function getRandomDirection() {
        if ($this->allowOriginalRandomCalculation) {
            return parent::getRandomDirection();
        }
        if (sizeof($this->directions) === 0) {
            Assert::fail('A configured Direction is missing');
        }
        $direction = $this->directions[0];
        unset($this->directions[0]);
        $this->directions = array_values($this->directions);
        return $direction;
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
}