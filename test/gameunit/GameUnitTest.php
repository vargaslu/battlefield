<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameUnit.php';

use Game\Battleship\GameUnit;
use PHPUnit\Framework\TestCase;

class GameUnitTest extends TestCase {

    public function testConstruction() {
        $gameUnit = new GameUnit();
        self::assertEquals(5, $gameUnit->availableShips());
    }
}
