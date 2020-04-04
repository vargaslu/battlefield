<?php

namespace Tests\Battleship\GameUnit;

require_once __DIR__.'/../../src/gameunit/GameUnit.php';

use Battleship\GameUnit\GameUnit;
use PHPUnit\Framework\TestCase;

class GameUnitTest extends TestCase {

    public function testConstruction() {
        $gameUnit = new GameUnit();
        self::assertEquals(5, $gameUnit->availableShips());
    }
}
