<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameController.php';

use Game\Battleship\Carrier;
use Game\Battleship\Destroyer;
use Game\Battleship\Direction;
use Game\Battleship\GameConstants;
use Game\Battleship\GameController;
use Game\Battleship\Location;
use Game\Battleship\PlacingShipsState;
use Game\Battleship\WaitingForStartState;
use PHPUnit\Framework\TestCase;

class GameControllerTest extends TestCase {

    protected function setUp(): void {
        GameConstants::$DEFAULT_SHIPS_TO_PLACE = [Carrier::NAME, Destroyer::NAME];
    }

    public function testInitialState() {
        $gameController = new GameController();
        self::assertTrue($gameController->getState() instanceof WaitingForStartState);
    }

    public function testPlacingShipsState() {
        $gameController = new GameController();
        $gameController->start();
        self::assertTrue($gameController->getState() instanceof PlacingShipsState);
    }

    public function testCallingShotsState() {
        $gameController = new GameController();
        $gameController->start();
        $gameController->placeShip(Carrier::build(new Location('A', 1), Direction::VERTICAL));
        $gameController->placeShip(Destroyer::build(new Location('A', 2), Direction::VERTICAL));

        self::assertFalse($gameController->getState() instanceof PlacingShipsState);
    }
}
