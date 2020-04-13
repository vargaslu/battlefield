<?php

namespace Tests\Game\Battleship;

require_once __DIR__.'/../../src/positioning/Direction.php';
require_once __DIR__.'/../../src/positioning/Location.php';
require_once __DIR__.'/../../src/positioning/ShipLocation.php';

use InvalidArgumentException;
use Game\Battleship\Direction;
use Game\Battleship\ShipLocation;
use PHPUnit\Framework\TestCase;

class ShipLocationTest extends TestCase {

    public function testCreate() {
        $shipLocation = new ShipLocation('A', 5, Direction::VERTICAL);
        self::assertEquals('A', $shipLocation->getLetter());
        self::assertEquals(5, $shipLocation->getColumn());
        self::assertEquals(Direction::VERTICAL, $shipLocation->getDirection());

        $shipLocation = new ShipLocation('B', 5, 'V');
        self::assertEquals('B', $shipLocation->getLetter());
        self::assertEquals(5, $shipLocation->getColumn());
        self::assertEquals(Direction::VERTICAL, $shipLocation->getDirection());

        $shipLocation = new ShipLocation('c', 3, 'h');
        self::assertEquals('C', $shipLocation->getLetter());
        self::assertEquals(3, $shipLocation->getColumn());
        self::assertEquals(Direction::HORIZONTAL, $shipLocation->getDirection());
    }

    public function testExceptionWhenCreate() {
        $this->expectException(InvalidArgumentException::class);

        new ShipLocation('A', 5, 3);
    }

    public function testIncreaseHorizontal() {
        $shipLocation = new ShipLocation('C', 3, 'H');
        $shipLocation->increase(3);
        self::assertEquals('C', $shipLocation->getLetter());
        self::assertEquals(6, $shipLocation->getColumn());
    }

    public function testIncreaseVertical() {
        $shipLocation = new ShipLocation('C', 3, 'V');
        $shipLocation->increase(3);
        self::assertEquals('F', $shipLocation->getLetter());
        self::assertEquals(3, $shipLocation->getColumn());
    }
}
