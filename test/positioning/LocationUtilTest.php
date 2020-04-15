<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/exceptions/InvalidLocationException.php';
require_once __DIR__ . '/../../src/positioning/Direction.php';
require_once __DIR__ . '/../../src/positioning/Location.php';
require_once __DIR__ . '/../../src/positioning/LocationUtils.php';

use Game\Battleship\Direction;
use Game\Battleship\InvalidLocationException;
use Game\Battleship\Location;
use Game\Battleship\LocationUtils;
use PHPUnit\Framework\TestCase;

class LocationUtilTest extends TestCase {

    public function testNoChangeInObjectWithIncrease() {
        $location = new Location('B', 4);
        LocationUtils::increase($location, Direction::VERTICAL);
        self::assertEquals('B', $location->getLetter());
        self::assertEquals(4, $location->getColumn());
    }

    public function testIncreaseVertical() {
        $location = new Location('B', 4);
        $newLocation = LocationUtils::increase($location, Direction::VERTICAL);
        self::assertEquals('C', $newLocation->getLetter());
        self::assertEquals(4, $newLocation->getColumn());
    }

    public function testIncreaseHorizontal() {
        $location = new Location('B', 4);
        $newLocation = LocationUtils::increase($location, Direction::HORIZONTAL);
        self::assertEquals('B', $newLocation->getLetter());
        self::assertEquals(5, $newLocation->getColumn());
    }

    public function testDecreaseVertical() {
        $location = new Location('B', 4);
        $newLocation = LocationUtils::decrease($location, Direction::VERTICAL);
        self::assertEquals('A', $newLocation->getLetter());
        self::assertEquals(4, $newLocation->getColumn());
    }

    public function testDecreaseHorizontal() {
        $location = new Location('B', 4);
        $newLocation = LocationUtils::decrease($location, Direction::HORIZONTAL);
        self::assertEquals('B', $newLocation->getLetter());
        self::assertEquals(3, $newLocation->getColumn());
    }

    public function testInvalidLetterSize() {
        $this->expectException(InvalidLocationException::class);
        LocationUtils::validateLetter("ABC");
    }

    public function testInvalidLetterValue() {
        $this->expectException(InvalidLocationException::class);
        LocationUtils::validateLetter("1");
    }

    public function testInvalidColumnValue() {
        $this->expectException(InvalidLocationException::class);
        LocationUtils::validateColumn(-1);
    }

    public function testInvalidIncreaseColumnLocation() {
        $this->expectException(InvalidLocationException::class);
        $location = new Location('G', 8);
        LocationUtils::increase($location, Direction::HORIZONTAL);
    }

    public function testInvalidIncreaseLetterLocation() {
        $this->expectException(InvalidLocationException::class);
        $location = new Location('H', 8);
        LocationUtils::increase($location, Direction::VERTICAL);
    }

    public function testInvalidDecreaseColumnLocation() {
        $this->expectException(InvalidLocationException::class);
        $location = new Location('A', 1);
        LocationUtils::decrease($location, Direction::HORIZONTAL);
    }

    public function testInvalidDecreaseLetterLocation() {
        $this->expectException(InvalidLocationException::class);
        $location = new Location('A', 1);
        LocationUtils::decrease($location, Direction::VERTICAL);
    }
}
