<?php

namespace Tests\Game\Battleship;

require_once __DIR__.'/../items/FakeShip.php';
require_once __DIR__.'/../../src/listeners/ShipDestroyedListener.php';
require_once __DIR__.'/../../src/positioning/ShipLocation.php';

use Game\Battleship\Direction;
use Game\Battleship\PropertyChangeListener;
use Game\Battleship\ShipLocation;
use Game\Battleship\ShipDestroyedListener;
use PHPUnit\Framework\TestCase;

class ShipDestroyedListenerTest extends TestCase {

    const FAKE_SHIP_1 = 'FakeShip1';
    const FAKE_SHIP_2 = 'FakeShip2';
    const FAKE_SHIP_3 = 'FakeShip3';

    private $fakeShip1;

    private $fakeShip2;

    private $fakeShip3;

    private $endGameListener;

    protected function setUp(): void {
        $this->endGameListener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();

        $this->fakeShip1 = new FakeShip(self::FAKE_SHIP_1, 2, new ShipLocation('A', 1, Direction::VERTICAL));
        $this->fakeShip2 = new FakeShip(self::FAKE_SHIP_2, 3, new ShipLocation('A', 3, Direction::HORIZONTAL));
        $this->fakeShip3 = new FakeShip(self::FAKE_SHIP_3, 2, new ShipLocation('A', 3, Direction::HORIZONTAL));

        $placedShips = [self::FAKE_SHIP_1 => $this->fakeShip1,
                        self::FAKE_SHIP_2 => $this->fakeShip2,
                        self::FAKE_SHIP_3 => $this->fakeShip3];
        $shipDestroyedListener = new ShipDestroyedListener($placedShips);
        $shipDestroyedListener->setEndGameListener($this->endGameListener);

        $this->fakeShip1->addPropertyChangeListener($shipDestroyedListener);
        $this->fakeShip2->addPropertyChangeListener($shipDestroyedListener);
        $this->fakeShip3->addPropertyChangeListener($shipDestroyedListener);
    }

    public function testNoNotificationWhenSomeShipsAreAlive() {
        $this->endGameListener->expects($this->never())->method('fireUpdate');

        $this->fakeShip2->hit();
        $this->fakeShip2->hit();
        $this->fakeShip2->hit();
    }

    function testNotifyListenersWhenAllShipsAreDestroyed() {
        $this->endGameListener->expects($this->once())->method('fireUpdate');

        $this->fakeShip2->hit();
        $this->fakeShip2->hit();
        $this->fakeShip2->hit();

        $this->fakeShip1->hit();
        $this->fakeShip1->hit();

        $this->fakeShip3->hit();
        $this->fakeShip3->hit();
    }
}
