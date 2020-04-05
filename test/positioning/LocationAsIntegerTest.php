<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/positioning/Location.php';
require_once __DIR__ . '/../../src/positioning/LocationAsInteger.php';

use Game\Battleship\Location;
use Game\Battleship\LocationAsInteger;
use PHPUnit\Framework\TestCase;

class LocationAsIntegerTest extends TestCase {

    public function testLetterAndColumnAsInteger() {
        $location = new Location("B", 3);
        $locationInteger = new LocationAsInteger($location);
        self::assertEquals(2, $locationInteger->getLetter());
        self::assertEquals(3, $locationInteger->getColumn());
    }

    public function testIncreaseLetter() {
        $location = new Location("B", 3);
        $locationInteger = new LocationAsInteger($location);
        $locationInteger->increaseLetter();
        self::assertEquals(3, $locationInteger->getLetter());
        self::assertEquals("C", $location->getLetter());
    }
}
