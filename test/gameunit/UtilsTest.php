<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/Utils.php';
require_once __DIR__ . '/../../src/gameunit/Grid.php';
require_once __DIR__ . '/../../src/positioning/Direction.php';

use Game\Battleship\Direction;
use Game\Battleship\Utils;
use Game\Battleship\Grid;
use Game\Battleship\Location;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase {

    public function testRandomLocation() {
        $location = Utils::getRandomLocation();
        self::assertTrue($location->getColumn() >= 1 && $location->getColumn() <= Grid::getSize());
        self::assertTrue(strpos('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $location->getLetter()) !== false);
    }

}
