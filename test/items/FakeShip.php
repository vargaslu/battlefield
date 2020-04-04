<?php


namespace Tests\Game\Battleship;

require_once __DIR__.'/../../src/items/Ship.php';

use Game\Battleship\Ship;

final class FakeShip extends Ship {

    public function __construct($name, $size) {
        parent::__construct($name, $size);
    }

    static function default() {
        return new FakeShip("FakeShip", 2);
    }
}