<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/gameunit/Constants.php';
require_once __DIR__ . '/../../src/player/PlayerEmulator.php';
require_once 'NotRandomPlayerEmulator.php';

use Game\Battleship\Carrier;
use Game\Battleship\Destroyer;
use Game\Battleship\Constants;
use Game\Battleship\Direction;
use Game\Battleship\GameService;
use Game\Battleship\GameUnit;
use Game\Battleship\HitResult;
use Game\Battleship\Location;
use Game\Battleship\ShipLocation;
use Game\Battleship\PropertyChangeListener;
use PHPUnit\Framework\TestCase;

class PlayerEmulatorTest extends TestCase {

    private $playerEmulator;

    private $gameUnit;

    private $mockedGameService;

    protected function setUp(): void {
        $this->mockedGameService = $this->getMockBuilder(GameService::class)->getMock();
        $this->gameUnit = new GameUnit($this->mockedGameService);

        Constants::$DEFAULT_SHIPS_TO_PLACE = [Carrier::NAME, Destroyer::NAME];

        $this->playerEmulator = new NotRandomPlayerEmulator($this->gameUnit);
    }

    public function testPlacingWithRandomLocations() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->gameUnit->setReadyListener($listener);
        $this->playerEmulator->placeShips();
    }

    public function testPlacingWithoutRandomLocations() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->playerEmulator->disableOriginalRandomCalculations();
        $this->playerEmulator->setDesiredShipLocations([new ShipLocation('A', 1, Direction::VERTICAL),
                                                    new ShipLocation('A', 2, Direction::VERTICAL)]);

        $this->gameUnit->setReadyListener($listener);
        $this->playerEmulator->placeShips();
    }

    public function testSearchAnotherPlaceIfExceptionIsCatch() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->playerEmulator->disableOriginalRandomCalculations();
        $this->playerEmulator->setDesiredShipLocations([new ShipLocation('A', 1, Direction::VERTICAL),
                                                    new ShipLocation('B', 1, Direction::VERTICAL),
                                                    new ShipLocation('A', 2, Direction::VERTICAL)]);

        $this->gameUnit->setReadyListener($listener);
        $this->playerEmulator->placeShips();
    }
}
