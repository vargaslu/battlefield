<?php

namespace Tests\Game\Battleship;

require_once __DIR__.'/../items/FakeItem.php';
require_once __DIR__.'/../../src/gameunit/Grid.php';
require_once __DIR__.'/../../src/exceptions/LocationException.php';
require_once __DIR__.'/../../src/exceptions/LocationOutOfBoundsException.php';

use Game\Battleship\LocationException;
use Game\Battleship\LocationOutOfBoundsException;
use Tests\Game\Battleship\FakeItem;
use Game\Battleship\Grid;
use Game\Battleship\Location;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase {

    private $item1;

    private $item2;

    protected function setUp(): void {
        Grid::setSize(4);
        $this->item1 = new FakeItem("item1",1);
        $this->item2 = new FakeItem("item2",1);
    }

    public function testSuccessfulGridFilling() {
        $grid = new Grid();
        $grid->put($this->item1, new Location("A", 2));
        $grid->put($this->item2, new Location("B", 2));

        $expectedItem = $grid->getItem(new Location('A', 2));
        self::assertEquals("item1", $expectedItem);

        $expectedItem = $grid->getItem(new Location('B', 2));
        self::assertEquals("item2", $expectedItem);

        self::assertEquals("item1:A-2 item2:B-2", $grid->asString());
    }

    public function testExceptionWhenItemIsOutsideOfTheGrid() {
        $this->expectException(LocationException::class);
        $grid = new Grid();
        $grid->put($this->item1, new Location("A", 5));
    }

    public function testExceptionWhenTryingToUseAnOccupiedLocation() {
        $this->expectException(LocationException::class);
        $grid = new Grid();
        $grid->put($this->item1, new Location("B", 2));
        $grid->put($this->item2, new Location("B", 2));
    }

    public function testToString() {
        $grid = new Grid();
        $grid->put($this->item1, new Location("B", 2));
        $grid->put($this->item2, new Location("C", 3));

        self::assertEquals("item1:B-2 item2:C-3", $grid->asString());
    }

    public function testExceptionWhenGettingAnItemOutsideOfTheGrid() {
        $this->expectException(LocationOutOfBoundsException::class);
        $grid = new Grid();

        $grid->getItem(new Location('B', 7));
    }

    public function testFilteredBoard() {
        $grid = new Grid();
        $grid->put($this->item1, new Location("B", 2));
        $grid->put($this->item1, new Location("C", 2));
        $grid->put($this->item2, new Location("C", 3));

        $filteredGrid = $grid->getFilteredGrid(function ($var) {
            return strcmp($var, 'item1') == 0;
        });

        self::assertEquals(1, sizeof($filteredGrid));
    }
}
