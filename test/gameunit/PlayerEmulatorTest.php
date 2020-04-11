<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/PlayerEmulator.php';
require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/gameunit/GameConstants.php';

use Game\Battleship\Carrier;
use Game\Battleship\Destroyer;
use Game\Battleship\GameConstants;
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

        GameConstants::$DEFAULT_SHIPS_TO_PLACE = [Carrier::NAME, Destroyer::NAME];

        $this->playerEmulator = new PlayerEmulator();
        $this->playerEmulator->setGameUnit($this->gameUnit);
    }

    public function testPlacing() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->playerEmulator->addPropertyChangeListener($listener)->placeShips();
    }
}
