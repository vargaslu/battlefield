<?php

namespace battleship\test\gameunit;

require_once '../../src/gameunit/Grid.php';
require_once '../../src/exceptions/LocationException.php';

use battleship\exceptions\LocationException;
use battleship\gameunit\Grid;
use battleship\gameunit\Location;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase {

    protected function setUp(): void {
        Grid::setSize(4);
    }

    public function testSuccessfulGridFilling() {
        $this->expectNotToPerformAssertions();

        $grid = new Grid();
        $grid->put("item", new Location("A", 2));
        $grid->put("item2", new Location("B", 2));
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

    public function testSuccessfulGridFillingFromTo() {
        $this->expectNotToPerformAssertions();

        $grid = new Grid();
        $grid->putFromTo("item", new Location("A", 2), new Location("A", 3));
        $grid->putFromTo("item2", new Location("B", 2), new Location("C", 2));
    }
}
