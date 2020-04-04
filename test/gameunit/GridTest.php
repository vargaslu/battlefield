<?php

namespace battleship\test\gameunit;

require_once __DIR__.'/../../src/gameunit/Grid.php';
require_once __DIR__.'/../../src/exceptions/LocationException.php';
require_once __DIR__.'/../../src/exceptions/InvalidLocationException.php';

use battleship\exceptions\InvalidLocationException;
use battleship\exceptions\LocationException;
use battleship\gameunit\Grid;
use battleship\gameunit\Location;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase {

    protected function setUp(): void {
        Grid::setSize(4);
    }

    public function testSuccessfulGridFilling() {
        $grid = new Grid();
        $grid->put("item1", new Location("A", 2));
        $grid->put("item2", new Location("B", 2));

        self::assertEquals("item1:A-2 item2:B-2", $grid->asString());
    }

    public function testExceptionWhenItemIsOutsideOfTheGrid() {
        $this->expectException(LocationException::class);
        $grid = new Grid();
        $grid->put("item", new Location("A", 5));
    }

    public function testExceptionWhenTryingToUseAnOccupiedLocation() {
        $this->expectException(LocationException::class);
        $grid = new Grid();
        $grid->put("item1", new Location("B", 2));
        $grid->put("item2", new Location("B", 2));
    }

    public function testToString() {
        $grid = new Grid();
        $grid->put("item1", new Location("B", 2));
        $grid->put("item2", new Location("C", 3));

        self::assertEquals("item1:B-2 item2:C-3", $grid->asString());
    }
}
