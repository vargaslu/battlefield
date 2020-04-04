<?php

namespace Tests\Game\Battleship;

require_once 'FakeShip.php';

use PHPUnit\Framework\TestCase;

class ShipTest extends TestCase {

    public function testShipCreation() {
        $fakeShip = FakeShip::default();
        self::assertEquals("FakeShip", $fakeShip->getName());
        self::assertEquals(2, $fakeShip->getSize());
        self::assertEquals(true, $fakeShip->isAlive());
    }

    public function testHitToSink() {
        $fakeShip = FakeShip::default();
        $fakeShip->hit();
        $fakeShip->hit();
        self::assertEquals(false, $fakeShip->isAlive());
    }

}
