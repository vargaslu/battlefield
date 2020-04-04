<?php


namespace battleship\gameunit;

require_once 'Ship.php';
require_once 'Grid.php';
require_once 'Location.php';
require_once 'Position.php';

class Ocean {

    private $grid;

    function __construct(Grid $grid) {
        $this->grid = $grid;
    }

    function place(Ship $ship, Location $location, $position) {
        // TODO: Validations before placing.
        // 1. Verify if ship is available
        $nextLocation = Location::of($location);
        for ($size = 0; $size < $ship->getSize(); $size++) {
            $this->grid->put($ship->getName(), $nextLocation);
            $nextLocation = $this->calculateNextLocation($nextLocation, $position);
        }
    }

    private function calculateNextLocation(Location $location, $position) {
        $newLocation = Location::of($location);

        if ($position == Position::HORIZONTAL) {
            $newLocation->increaseRow();
        } else if ($position == Position::VERTICAL){
            $newLocation->increaseColumn();
        } else {
            throw new Exception("Invalid Position value");
        }
        return $newLocation;
    }
}