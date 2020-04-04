<?php


namespace Battleship\GameUnit;

require_once 'Battleship.php';
require_once 'Carrier.php';
require_once 'Cruiser.php';
require_once 'Submarine.php';
require_once 'Destroyer.php';

class GameUnit {

    private $ocean;

    private $target;

    private $ships;

    public function __construct() {
        $this->ocean = new Ocean(new Grid());
        $this->target = new Target(new Grid());
        $this->ships = array(new Carrier(),
                            new Battleship(),
                            new Cruiser(),
                            new Submarine(),
                            new Destroyer());
    }

    public function placeShip(Ship $ship, Location $location) {
        // TODO: Validations before placing.
        // 1. Verify if ship is available
    }

    public function makeShot(Location $location) {
        // TODO: mark in target
        // TODO: Service to verifyShot in opponent
    }

    public function verifyShot(Location $location) {
        // TODO: Is a hit or miss, if hit return name of ship
    }

    public function availableShips() {
        return sizeof($this->ships);
    }
}