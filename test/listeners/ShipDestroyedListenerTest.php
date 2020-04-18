<?php

namespace Tests\Game\Battleship;

require_once __DIR__.'/../items/FakeShip.php';
require_once __DIR__.'/../../src/listeners/ShipDestroyedListener.php';
require_once __DIR__.'/../../src/positioning/ShipLocation.php';

use Game\Battleship\Direction;
use Game\Battleship\PropertyChangeListener;
use Game\Battleship\Ship;
use Game\Battleship\ShipLocation;
use Game\Battleship\ShipDestroyedListener;
use PHPUnit\Framework\TestCase;

class ShipDestroyedListenerTest extends TestCase {

    const FAKE_SHIP_1 = 'FakeShip1';
    const FAKE_SHIP_2 = 'FakeShip2';

    function testNotifyListenersWhenAllShipsAreDestroyed() {
        $endGameListener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $endGameListener->expects($this->once())->method('fireUpdate');

        $fakeShip1 = new FakeShip(self::FAKE_SHIP_1, 2, new ShipLocation('A', 1, Direction::VERTICAL));
        $fakeShip2 = new FakeShip(self::FAKE_SHIP_2, 1, new ShipLocation('A', 3, Direction::HORIZONTAL));
        $placedShips = [self::FAKE_SHIP_1 => $fakeShip1, self::FAKE_SHIP_2 => $fakeShip2];
        $shipDestroyedListener = new ShipDestroyedListener($placedShips);
        $shipDestroyedListener->setEndGameListener($endGameListener);

        $fakeShip1->addPropertyChangeListener($shipDestroyedListener);
        $fakeShip2->addPropertyChangeListener($shipDestroyedListener);

        $fakeShip1->hit();
        $fakeShip1->hit();
        $fakeShip2->hit();
    }
}
