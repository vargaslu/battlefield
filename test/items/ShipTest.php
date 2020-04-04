<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/items/Destroyer.php';

use Game\Battleship\Destroyer;
use PHPUnit\Framework\TestCase;

class ShipTest extends TestCase {

    public function testShipCreation() {
        $destroyer = new Destroyer();
        self::assertEquals("Destroyer", $destroyer->getName());
        self::assertEquals(2, $destroyer->getSize());
        self::assertEquals(true, $destroyer->isAlive());
    }

    public function testHitToSink() {
        $destroyer = new Destroyer();
        $destroyer->hit();
        $destroyer->hit();
        self::assertEquals(false, $destroyer->isAlive());
    }

}
