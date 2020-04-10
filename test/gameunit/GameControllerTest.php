<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameController.php';

use Game\Battleship\GameController;
use PHPUnit\Framework\TestCase;

class GameControllerTest extends TestCase {

    public function testStart() {
        new GameController();
    }
}
