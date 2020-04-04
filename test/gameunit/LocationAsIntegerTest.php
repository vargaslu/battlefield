<?php

namespace battleship\test\gameunit;

require_once '../../src/gameunit/Location.php';
require_once '../../src/gameunit/LocationAsInteger.php';

use battleship\gameunit\Location;
use battleship\gameunit\LocationAsInteger;
use PHPUnit\Framework\TestCase;

class LocationAsIntegerTest extends TestCase {

    public function testColumnAndRowAsInteger() {
        $location = new Location("B", 3);
        $locationInteger = new LocationAsInteger($location);
        self::assertEquals(2, $locationInteger->getColumn());
        self::assertEquals(3, $locationInteger->getRow());
    }

    public function testIncreaseColumn() {
        $location = new Location("B", 3);
        $locationInteger = new LocationAsInteger($location);
        $locationInteger->increaseColumn();
        self::assertEquals(3, $locationInteger->getColumn());
        self::assertEquals("C", $location->getColumn());
    }
}
