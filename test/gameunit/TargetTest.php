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
        $target->place(Peg::createRedPeg(), new Location("A", 6));
    }

    public function testExceptionWhenPegIsOutsideGridVertically() {
        $this->expectException(LocationException::class);

        $target = new Target(new Grid());
        $target->place(Peg::createWhitePeg(), new Location("G", 1));
    }

}
