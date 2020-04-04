<?php

namespace battleship\test\gameunit;

include '../../src/gameunit/Carrier.php';
include '../../src/gameunit/Destroyer.php';
include '../../src/gameunit/Ocean.php';
include '../../src/exceptions/LocationException.php';

use battleship\gameunit\Grid;
use battleship\gameunit\Ocean;
use battleship\gameunit\Location;
use battleship\gameunit\Position;
use battleship\exceptions\LocationException;
use battleship\gameunit\Carrier;
use battleship\gameunit\Destroyer;
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
