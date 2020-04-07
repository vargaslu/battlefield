<?php


namespace Tests\Game\Battleship;

require_once __DIR__.'/../../src/items/Ship.php';
require_once __DIR__.'/../../src/positioning/Location.php';
require_once __DIR__.'/../../src/positioning/Direction.php';

use Game\Battleship\Direction;
use Game\Battleship\Location;
use Game\Battleship\Ship;

final class FakeShip extends Ship {

    public function __construct($name, $size, Location $location, $direction) {
        parent::__construct($name, $size, $location, $direction);
    }

    static function defaultVertical(Location $location) {
        return new FakeShip("FakeShip", 2, $location, Direction::VERTICAL);
    }

    static function defaultHorizontal(Location $location) {
        return new FakeShip("FakeShip", 2, $location, Direction::HORIZONTAL);
    }
}