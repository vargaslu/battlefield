<?php

namespace Tests\Game\Battleship;

require_once __DIR__.'/../../src/listeners/PropertyChangeListener.php';
require_once __DIR__.'/../../src/positioning/Location.php';
require_once 'FakeShip.php';

use Game\Battleship\Direction;
use Game\Battleship\PropertyChangeListener;
use Game\Battleship\Location;
use Game\Battleship\ShipLocation;
use PHPUnit\Framework\TestCase;

class ShipTest extends TestCase {

    private $defaultLocation;

    protected function setUp(): void {
        $this->defaultLocation = new ShipLocation('A', '1', Direction::VERTICAL);
    }

    public function testShipCreation() {
        $fakeShip = FakeShip::defaultVertical($this->defaultLocation);
        self::assertEquals("FakeShip", $fakeShip->getName());
        self::assertEquals(2, $fakeShip->getSize());
        self::assertEquals(true, $fakeShip->isAlive());
        self::assertEquals(true,
                           $fakeShip->getLocation() == new ShipLocation('A',
                                                                        '1',
                                                                        Direction::VERTICAL));
    }

    public function testHitToSink() {
        $fakeShip = FakeShip::defaultVertical($this->defaultLocation);
        $fakeShip->hit();
        $fakeShip->hit();
        self::assertEquals(false, $fakeShip->isAlive());
    }

    public function testNotifyToListeners() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $fakeShip = FakeShip::defaultVertical($this->defaultLocation);
        $fakeShip->addPropertyChangeListener($listener);
        $fakeShip->hit();
        $fakeShip->hit();
    }

}
