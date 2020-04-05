<?php

namespace Tests\Game\Battleship;

require_once __DIR__.'/../../src/items/Peg.php';
require_once __DIR__.'/../../src/gameunit/Target.php';
require_once __DIR__.'/../../src/exceptions/LocationException.php';

use Game\Battleship\LocationException;
use Game\Battleship\Grid;
use Game\Battleship\Location;
use Game\Battleship\Peg;
use Game\Battleship\Target;
use PHPUnit\Framework\TestCase;

class TargetTest extends TestCase {

    protected function setUp(): void {
        Grid::setSize(5);
    }

    public function testExceptionWhenPegIsOutsideGridHorizontally() {
        $this->expectException(LocationException::class);

        $target = new Target(new Grid());
        $target->place(Peg::createRedPeg(), new Location('A', 6));
    }

    public function testExceptionWhenPegIsOutsideGridVertically() {
        $this->expectException(LocationException::class);

        $target = new Target(new Grid());
        $target->place(Peg::createWhitePeg(), new Location('G', 1));
    }

    public function testGetFilteredPegs() {
        $target = new Target(new Grid());
        $target->place(Peg::createRedPeg(), new Location('A', 2));
        $target->place(Peg::createWhitePeg(), new Location('D', 3));
        $target->place(Peg::createWhitePeg(), new Location('C', 5));

        self::assertEquals(2, sizeof($target->getWhitePegs()));
        self::assertEquals(1, sizeof($target->getRedPegs()));
    }

    public function testPeekFromGrid() {
        $target = new Target(new Grid());
        $target->place(Peg::createRedPeg(), new Location('A', 2));
        $target->place(Peg::createWhitePeg(), new Location('D', 3));

        self::assertEquals(Peg::RED, $target->peek(new Location('A', 2)));
        self::assertEquals(Peg::WHITE, $target->peek(new Location('D', 3)));
    }
}
