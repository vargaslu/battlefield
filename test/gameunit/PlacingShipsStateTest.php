<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/gameunit/GameService.php';
require_once __DIR__ . '/../../src/gameunit/GameController.php';
require_once __DIR__ . '/../../src/gameunit/PlacingShipsState.php';
require_once __DIR__ . '/../../src/exceptions/GameStateException.php';
require_once __DIR__ . '/../../src/exceptions/NotAllowedShipException.php';

use Game\Battleship\PlayerEmulator;
use Game\Battleship\Carrier;
use Game\Battleship\Destroyer;
use Game\Battleship\Direction;
use Game\Battleship\GameController;
use Game\Battleship\GameService;
use Game\Battleship\GameState;
use Game\Battleship\GameStateException;
use Game\Battleship\GameUnit;
use Game\Battleship\GameUtils;
use Game\Battleship\Location;
use Game\Battleship\NotAllowedShipException;
use Game\Battleship\PlacingShipsState;
use Game\Battleship\PropertyChangeListener;
use Game\Battleship\Submarine;
use PHPUnit\Framework\TestCase;

class PlacingShipsStateTest extends TestCase {

    private $mockedGameService;

    private $current;

    private $placingShipsState;

    protected function setUp(): void {
        $this->mockedGameService = $this->getMockBuilder(GameService::class)->getMock();
        $this->current = new GameUnit($this->mockedGameService);

        $this->placingShipsState = new PlacingShipsState();
        $this->placingShipsState->setShipsToPlace([Carrier::NAME, Destroyer::NAME]);
    }

    public function testPlaceShips() {
        $this->placingShipsState->placingShips($this->current,
                                               Carrier::build(new Location('A', 1),
                                                              Direction::VERTICAL));
        self::assertEquals(1, $this->current->availableShips());
    }

    public function testNotifyListenerWhenAllShipsArePlaced() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->placingShipsState->addPropertyChangeListener($listener);

        $this->placingShipsState->placingShips($this->current,
                                               Carrier::build(new Location('A', 1),
                                                              Direction::VERTICAL));
        $this->placingShipsState->placingShips($this->current,
                                               Destroyer::build(new Location('A', 2),
                                                                Direction::VERTICAL));
    }

    public function testExceptionCannotPlaceUnwantedShip() {
        try {
            $this->placingShipsState->placingShips($this->current,
                                                   Carrier::build(new Location('A', 1),
                                                                  Direction::VERTICAL));
            $this->placingShipsState->placingShips($this->current,
                                                   Submarine::build(new Location('A', 2),
                                                                    Direction::VERTICAL));
        } catch (NotAllowedShipException $exception) {
            self::assertEquals('Ship Submarine not allowed', $exception->getMessage());
            self::assertEquals(1, $this->current->availableShips());
        }
    }

    public function testExceptionWhenTryingToCallShotsWhilePlacingShips() {
        $this->expectException(GameStateException::class);

        $this->placingShipsState->callingShot(new Location('A', 1));
    }
}
