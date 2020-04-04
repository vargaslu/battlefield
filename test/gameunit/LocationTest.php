<?php

namespace battleship\test\gameunit;

require_once __DIR__.'/../../src/gameunit/Location.php';

use battleship\gameunit\Location;
use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase {

    public function testLocationCreation() {
        $location = new Location("A" , 1);
        self::assertEquals("A", $location->getColumn());
        self::assertEquals(1, $location->getRow());
    }

    public function testUppercaseOfLocationCreationColumn() {
        $location = new Location("b" , 2);
        self::assertEquals("B", $location->getColumn());
        self::assertEquals(2, $location->getRow());
    }

    public function testLocationToString() {
        $location = new Location("c" , 3);
        self::assertEquals("C-3", $location);
    }

    public function testIncreaseColumn() {
        $location = new Location("D" , 3);
        $location->increaseColumn();
        self::assertEquals("E-3", $location);
    }

    public function testIncreaseRow() {
        $location = new Location("D" , 3);
        $location->increaseRow();
        self::assertEquals("D-4", $location);
    }

    public function testCopyCreation() {
        $location = new Location("D" , 3);
        $copiedLocation = Location::of($location);
        $copiedLocation->increaseColumn();
        $copiedLocation->increaseRow();

        self::assertNotEquals($location->getColumn(), $copiedLocation->getColumn());
        self::assertNotEquals($location->getRow(), $copiedLocation->getRow());
    }
}
