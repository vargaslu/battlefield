<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameControllerImpl.php';

use Game\Battleship\Carrier;
use Game\Battleship\Destroyer;
use Game\Battleship\Direction;
use Game\Battleship\Constants;
use Game\Battleship\GameControllerImpl;
use Game\Battleship\Location;
use Game\Battleship\PlacingShipsState;
use Game\Battleship\WaitingForStartState;
use PHPUnit\Framework\TestCase;

class GameControllerTest extends TestCase {

    protected function setUp(): void {
        Constants::$DEFAULT_SHIPS_TO_PLACE = [Carrier::NAME, Destroyer::NAME];
    }

    public function testInitialState() {
        $gameController = new GameControllerImpl();
        self::assertTrue($gameController->getCurrentState() instanceof WaitingForStartState);
    }

    public function testPlacingShipsState() {
        $gameController = new GameControllerImpl();
        $gameController->start('');
        self::assertTrue($gameController->getCurrentState() instanceof PlacingShipsState);
    }

    public function testCallingShotsState() {
        $data = json_decode($this->getJsonCallForPlaceShips(), true);
        $gameController = new GameControllerImpl();
        $gameController->start('');

        foreach ($data as $jsonShip) {
            $gameController->placeShip($jsonShip);
        }

        self::assertFalse($gameController->getCurrentState() instanceof PlacingShipsState);
    }

    private function getJsonCallForPlaceShips() : string {
        return '
        [{
            "name" : "Carrier",
            "location": "A",
            "column": 1,
            "direction": "V"
        },
        {
            "name" : "Destroyer",
            "location": "A",
            "column": 2,
            "direction": "V"
        }]';
    }
}
