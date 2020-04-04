<?php

namespace Tests\Battleship\GameUnit;

require_once __DIR__.'/../../src/gameunit/Carrier.php';
require_once __DIR__.'/../../src/gameunit/Destroyer.php';
require_once __DIR__.'/../../src/gameunit/Ocean.php';
require_once __DIR__.'/../../src/exceptions/LocationException.php';

use Battleship\GameUnit\Grid;
use Battleship\GameUnit\Ocean;
use Battleship\GameUnit\Location;
use Battleship\GameUnit\Position;
use Battleship\Exceptions\LocationException;
use Battleship\GameUnit\Carrier;
use Battleship\GameUnit\Destroyer;
use PHPUnit\Framework\TestCase;

class OceanTest extends TestCase {

    protected function setUp(): void {
        Grid::setSize(5);
    }

    public function testExceptionWhenPartOfShipIsPlacedOutsideGridHorizontal() {
        $this->expectException(LocationException::class);

        $ocean = new Ocean(new Grid());
        $ocean->place(new Carrier(), new Location("A", 2), Position::HORIZONTAL);
    }

    public function testExceptionWhenPartOfShipIsPlacedOutsideGridVertical() {
        $this->expectException(LocationException::class);

        $ocean = new Ocean(new Grid());
        $ocean->place(new Carrier(), new Location("B", 1), Position::VERTICAL);
    }

    public function testExceptionWhenTwoShipsCollide() {
        $this->expectException(LocationException::class);

        $ocean = new Ocean(new Grid());
        $ocean->place(new Carrier(), new Location("B", 1), Position::HORIZONTAL);
        $ocean->place(new Destroyer(), new Location("A", 2), Position::VERTICAL);
    }
}
