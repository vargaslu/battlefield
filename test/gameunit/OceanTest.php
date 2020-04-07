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

    private const FAKE_SHIP1 = 'FakeShip1';

    private const FAKE_SHIP2 = 'FakeShip2';

    protected function setUp(): void {
        Grid::setSize(5);
    }

    public function testExceptionWhenPartOfShipIsPlacedOutsideGridHorizontal() {
        $this->expectException(LocationException::class);

        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 5, new Location('A', 2), Direction::HORIZONTAL);

        $ocean = new Ocean(new Grid());
        $ocean->place($fakeShip1);
    }

    public function testExceptionWhenPartOfShipIsPlacedOutsideGridVertical() {
        $this->expectException(LocationException::class);

        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 5, new Location('B', 1), Direction::VERTICAL);

        $ocean = new Ocean(new Grid());
        $ocean->place($fakeShip1);
    }

    public function testExceptionWhenTwoShipsCollide() {
        $this->expectException(LocationException::class);

        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 5, new Location('B', 1), Direction::HORIZONTAL);
        $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new Location('A', 2), Direction::VERTICAL);

        $ocean = new Ocean(new Grid());
        $ocean->place($fakeShip1);
        $ocean->place($fakeShip2);
    }

    public function testPeekFromGrid() {
        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 5, new Location('A', 1), Direction::HORIZONTAL);

        $ocean = new Ocean(new Grid());
        $ocean->place($fakeShip1);

        self::assertEquals(self::FAKE_SHIP1, $ocean->peek(new Location('A', 1)));
        self::assertEquals(self::FAKE_SHIP1, $ocean->peek(new Location('A', 2)));
        self::assertEquals(self::FAKE_SHIP1, $ocean->peek(new Location('A', 3)));
        self::assertEquals(self::FAKE_SHIP1, $ocean->peek(new Location('A', 4)));
        self::assertEquals('', $ocean->peek(new Location('B', 1)));
    }
}
