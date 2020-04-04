<?php

namespace Tests\Battleship\GameUnit;

require_once __DIR__.'/../../src/gameunit/Peg.php';
require_once __DIR__.'/../../src/gameunit/Target.php';

use Battleship\Exceptions\LocationException;
use Battleship\GameUnit\Grid;
use Battleship\GameUnit\Location;
use Battleship\GameUnit\Peg;
use Battleship\GameUnit\Target;
use PHPUnit\Framework\TestCase;

class TargetTest extends TestCase {

    protected function setUp(): void {
        Grid::setSize(5);
    }

    public function testExceptionWhenPegIsOutsideGridHorizontally() {
        $this->expectException(LocationException::class);

        $target = new Target(new Grid());
        $target->place(new Peg(), new Location("A", 6));
    }

    public function testExceptionWhenPegIsOutsideGridVertically() {
        $this->expectException(LocationException::class);

        $target = new Target(new Grid());
        $target->place(new Peg(), new Location("G", 1));
    }

}
