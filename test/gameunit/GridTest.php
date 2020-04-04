<?php

namespace Tests\Battleship\GameUnit;

require_once __DIR__.'/../../src/gameunit/Grid.php';
require_once __DIR__.'/../../src/exceptions/LocationException.php';
require_once __DIR__.'/../../src/exceptions/InvalidLocationException.php';
require_once __DIR__.'/FakeItem.php';

use Battleship\Exceptions\InvalidLocationException;
use Battleship\Exceptions\LocationException;
use Tests\Battleship\GameUnit\FakeItem;
use Battleship\GameUnit\Grid;
use Battleship\GameUnit\Location;
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
}
