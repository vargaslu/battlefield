<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameUtils.php';
require_once __DIR__ . '/../../src/gameunit/Grid.php';
require_once __DIR__ . '/../../src/positioning/Direction.php';

use Game\Battleship\Direction;
use Game\Battleship\GameUtils;
use Game\Battleship\Grid;
use Game\Battleship\Location;
use PHPUnit\Framework\TestCase;

class GameUtilsTest extends TestCase {

    public function testRandomLocation() {
        $location = GameUtils::getRandomLocation();
        self::assertTrue($location->getColumn() >= 1 && $location->getColumn() <= Grid::getSize());
        self::assertTrue(strpos('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $location->getLetter()) !== false);
    }

    public function testMockRandomLocation() {
        $gameUtilsMocked = $this->createStub(GameUtils::class);
        $gameUtilsMocked->method('getRandomDirection')
                        ->willReturn(Direction::VERTICAL);
        $gameUtilsMocked->method('getRandomLocation')
                        ->willReturn(new Location('D', 4));

        $location = $gameUtilsMocked->getRandomLocation();
        self::assertEquals(1, $gameUtilsMocked->getRandomDirection());
        self::assertEquals('D', $location->getLetter());
        self::assertEquals(4, $location->getColumn());
    }
}
