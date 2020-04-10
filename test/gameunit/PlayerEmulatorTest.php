<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/PlayerEmulator.php';
require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/gameunit/GameConstants.php';

use Game\Battleship\Carrier;
use Game\Battleship\Destroyer;
use Game\Battleship\GameService;
use Game\Battleship\GameUnit;
use Game\Battleship\PlayerEmulator;
use Game\Battleship\PropertyChangeListener;
use PHPUnit\Framework\TestCase;

class PlayerEmulatorTest extends TestCase {

    private $playerEmulator;

    private $gameUnit;

    protected function setUp(): void {
        $mockedGameService = $this->getMockBuilder(GameService::class)->getMock();
        $this->gameUnit = new GameUnit($mockedGameService);

        $this->playerEmulator = new PlayerEmulator($this->gameUnit);
        $this->playerEmulator->setShipsToPlace([Carrier::NAME, Destroyer::NAME]);
    }

    public function testPlacing() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->playerEmulator->addPropertyChangeListener($listener)->placeShips();
    }
}
