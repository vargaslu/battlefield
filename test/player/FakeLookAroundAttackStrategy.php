<?php


namespace Tests\Game\Battleship;

use Game\Battleship\GameUnit;
use Game\Battleship\Location;
use Game\Battleship\LookAroundAttackStrategy;
use PHPUnit\Framework\Assert;

class FakeLookAroundAttackStrategy extends LookAroundAttackStrategy {

    private $locations;

    public function __construct(GameUnit $gameUnit) {
        parent::__construct($gameUnit);
    }

    public function setRandomShotLocations($locations) {
        $this->locations = $locations;
    }

    protected function getRandomLocation(): Location {
        if (!isset($this->locations) || sizeof($this->locations) === 0) {
            Assert::fail('A configured Location is missing');
        }
        $location = $this->locations[0];
        unset($this->locations[0]);
        $this->locations = array_values($this->locations);
        return $location;
    }

    public function verifyShot(Location $location) {
        return $this->getGameUnit()->isTargetLocationMarked($location);
    }
}