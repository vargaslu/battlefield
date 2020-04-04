<?php

namespace Tests\Game\Battleship;

require_once __DIR__.'/../../src/gameunit/Ocean.php';
require_once __DIR__.'/../../src/exceptions/LocationException.php';
require_once __DIR__.'/../items/FakeShip.php';

use Game\Battleship\Grid;
use Game\Battleship\Ocean;
use Game\Battleship\Location;
use Game\Battleship\Direction;
use Game\Battleship\LocationException;
use PHPUnit\Framework\TestCase;

class OceanTest extends TestCase {

    private $fakeShip1;

    private $fakeShip2;

    protected function setUp(): void {
        Grid::setSize(5);
        $this->fakeShip1 = new FakeShip("FakeShip1", 5);
        $this->fakeShip2 = new FakeShip("FakeShip2", 2);
    }

    public function testExceptionWhenPartOfShipIsPlacedOutsideGridHorizontal() {
        $this->expectException(LocationException::class);

        $ocean = new Ocean(new Grid());
        $ocean->place($this->fakeShip1, new Location("A", 2), Direction::HORIZONTAL);
    }

    public function testExceptionWhenPartOfShipIsPlacedOutsideGridVertical() {
        $this->expectException(LocationException::class);

        $ocean = new Ocean(new Grid());
        $ocean->place($this->fakeShip1, new Location("B", 1), Direction::VERTICAL);
    }

    public function testExceptionWhenTwoShipsCollide() {
        $this->expectException(LocationException::class);

        $ocean = new Ocean(new Grid());
        $ocean->place($this->fakeShip1, new Location("B", 1), Direction::HORIZONTAL);
        $ocean->place($this->fakeShip2, new Location("A", 2), Direction::VERTICAL);
    }

    public function testPeekFromGrid() {
        $ocean = new Ocean(new Grid());
        $ocean->place($this->fakeShip1, new Location('A', 1), Direction::HORIZONTAL);

        self::assertEquals('FakeShip1', $ocean->peek(new Location('A', 1)));
        self::assertEquals('FakeShip1', $ocean->peek(new Location('A', 2)));
        self::assertEquals('FakeShip1', $ocean->peek(new Location('A', 3)));
        self::assertEquals('FakeShip1', $ocean->peek(new Location('A', 4)));
        self::assertEquals('', $ocean->peek(new Location('B', 1)));
    }
}
