<?php


namespace Tests\Game\Battleship;

require_once __DIR__.'/../../src/items/Ship.php';
require_once __DIR__.'/../../src/positioning/Location.php';
require_once __DIR__.'/../../src/positioning/ShipLocation.php';
require_once __DIR__.'/../../src/positioning/Direction.php';

use Game\Battleship\Direction;
use Game\Battleship\Location;
use Game\Battleship\Ship;
use Game\Battleship\ShipLocation;

final class FakeShip extends Ship {

    public function __construct($name, $size, ShipLocation $location, $direction = null) {
        parent::__construct($name, $size, $location, $direction);
    }

    static function defaultVertical(ShipLocation $location) {
        return new FakeShip("FakeShip", 2, $location, Direction::VERTICAL);
    }

    static function defaultHorizontal(ShipLocation $location) {
        return new FakeShip("FakeShip", 2, $location, Direction::HORIZONTAL);
    }
}