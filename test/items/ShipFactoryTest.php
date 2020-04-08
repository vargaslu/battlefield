<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/items/ShipFactory.php';
require_once __DIR__ . '/../../src/items/Battleship.php';
require_once __DIR__ . '/../../src/items/Carrier.php';
require_once __DIR__ . '/../../src/items/Cruiser.php';
require_once __DIR__ . '/../../src/items/Destroyer.php';
require_once __DIR__ . '/../../src/items/Submarine.php';
require_once __DIR__ . '/../../src/positioning/Direction.php';
require_once __DIR__ . '/../../src/positioning/Location.php';

use Game\Battleship\Battleship;
use Game\Battleship\Carrier;
use Game\Battleship\Cruiser;
use Game\Battleship\Destroyer;
use Game\Battleship\Direction;
use Game\Battleship\Location;
use Game\Battleship\ShipFactory;
use Game\Battleship\Submarine;
use PHPUnit\Framework\TestCase;

class ShipFactoryTest extends TestCase {

    private $location;

    protected function setUp(): void {
        $this->location = new Location('A', 3);
    }

    public function testCarrierCreation() {
        $factory = new ShipFactory(Carrier::NAME);
        $ship = $factory->buildWithLocation($this->location, Direction::VERTICAL);
        self::assertTrue($ship instanceof Carrier);
    }

    public function testCruiserCreation() {
        $factory = new ShipFactory(Cruiser::NAME);
        $ship = $factory->buildWithLocation($this->location, Direction::VERTICAL);
        self::assertTrue($ship instanceof Cruiser);
    }

    public function testDestroyerCreation() {
        $factory = new ShipFactory(Destroyer::NAME);
        $ship = $factory->buildWithLocation($this->location, Direction::VERTICAL);
        self::assertTrue($ship instanceof Destroyer);
    }

    public function testBattleshipCreation() {
        $factory = new ShipFactory(Battleship::NAME);
        $ship = $factory->buildWithLocation($this->location, Direction::VERTICAL);
        self::assertTrue($ship instanceof Battleship);
    }

    public function testSubmarineCreation() {
        $factory = new ShipFactory(Submarine::NAME);
        $ship = $factory->buildWithLocation($this->location, Direction::VERTICAL);
        self::assertTrue($ship instanceof Submarine);
    }

    public function testExceptionForInvalidShipName() {
        $this->expectException(\InvalidArgumentException::class);

        $factory = new ShipFactory('Fake');
        $factory->buildWithLocation($this->location, Direction::VERTICAL);
    }
}
