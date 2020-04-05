<?php

namespace Tests\Game\Battleship;

require_once __DIR__.'/../../src/listeners/PropertyChangeListener.php';
require_once 'FakeShip.php';

use Game\Battleship\PropertyChangeListener;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

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

    public function testNotifyToListeners() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)
                        ->setMethods(['fireUpdate'])->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $fakeShip = FakeShip::default();
        $fakeShip->addPropertyChangeListener($listener);
        $fakeShip->hit();
        $fakeShip->hit();
    }

}
