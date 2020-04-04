<?php

namespace battleship\test\gameunit;

require_once __DIR__.'/../../src/gameunit/Peg.php';
require_once __DIR__.'/../../src/gameunit/Target.php';

use battleship\exceptions\LocationException;
use battleship\gameunit\Grid;
use battleship\gameunit\Location;
use battleship\gameunit\Peg;
use battleship\gameunit\Target;
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
