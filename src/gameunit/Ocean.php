<?php


namespace Game\Battleship;

require_once __DIR__.'/../items/Ship.php';
require_once __DIR__.'/../positioning/Location.php';
require_once __DIR__ . '/../positioning/Direction.php';
require_once 'Grid.php';

class Ocean {

    private $grid;

    function __construct(Grid $grid) {
        $this->grid = $grid;
    }

    function place(Ship $ship, Location $location, $direction) {
        $nextLocation = Location::of($location);
        for ($size = 0; $size < $ship->getSize(); $size++) {
            $this->grid->put($ship, $nextLocation);
            $nextLocation = $this->calculateNextLocation($nextLocation, $direction);
        }
    }

    private function calculateNextLocation(Location $location, $direction) {
        $newLocation = Location::of($location);

        if ($direction == Direction::HORIZONTAL) {
            $newLocation->increaseRow();
        } else if ($direction == Direction::VERTICAL){
            $newLocation->increaseColumn();
        } else {
            throw new Exception("Invalid Position value");
        }
        return $newLocation;
    }
}