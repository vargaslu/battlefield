<?php

namespace Tests\Game\Battleship;

require_once __DIR__.'/../../src/items/Carrier.php';
require_once __DIR__.'/../../src/items/Destroyer.php';
require_once __DIR__.'/../../src/gameunit/Ocean.php';
require_once __DIR__.'/../../src/exceptions/LocationException.php';

use Game\Battleship\Grid;
use Game\Battleship\Ocean;
use Game\Battleship\Location;
use Game\Battleship\Direction;
use Game\Battleship\LocationException;
use Game\Battleship\Carrier;
use Game\Battleship\Destroyer;
use PHPUnit\Framework\TestCase;

class OceanTest extends TestCase {

    protected function setUp(): void {
        Grid::setSize(5);
    }

    public function testExceptionWhenPartOfShipIsPlacedOutsideGridHorizontal() {
        $this->expectException(LocationException::class);

        $ocean = new Ocean(new Grid());
        $ocean->place(new Carrier(), new Location("A", 2), Direction::HORIZONTAL);
    }

    public function testExceptionWhenPartOfShipIsPlacedOutsideGridVertical() {
        $this->expectException(LocationException::class);

        $ocean = new Ocean(new Grid());
        $ocean->place(new Carrier(), new Location("B", 1), Direction::VERTICAL);
    }

    public function testExceptionWhenTwoShipsCollide() {
        $this->expectException(LocationException::class);

        $ocean = new Ocean(new Grid());
        $ocean->place(new Carrier(), new Location("B", 1), Direction::HORIZONTAL);
        $ocean->place(new Destroyer(), new Location("A", 2), Direction::VERTICAL);
    }
}
