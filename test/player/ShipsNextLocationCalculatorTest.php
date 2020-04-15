<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/player/ShipsNextLocationCalculator.php';
require_once __DIR__ . '/../../src/positioning/Location.php';

use Game\Battleship\Location;
use Game\Battleship\ShipsNextLocationCalculator;
use PHPUnit\Framework\TestCase;

class ShipsNextLocationCalculatorTest extends TestCase {

    public function testSuccessfulCreateCalculations() {
        $shipsNextLocationCalculator = new ShipsNextLocationCalculator();
        $shipsNextLocationCalculator->createCalculations("Carrier", new Location('B', 3), 3);

        self::assertEquals('[ B-3, A-3, B-2, C-3, B-4 ]', (string) $shipsNextLocationCalculator);
        self::assertEquals(3, $shipsNextLocationCalculator->getCurrentSize());
    }

    public function testCouldNotOverwriteInCreateCalculations() {
        $shipsNextLocationCalculator = new ShipsNextLocationCalculator();
        $shipsNextLocationCalculator->createCalculations("Carrier", new Location('B', 3), 3);
        self::assertEquals('[ B-3 ]', (string) $shipsNextLocationCalculator);

        $shipsNextLocationCalculator->createCalculations("Carrier", new Location('D', 3), 3);
        self::assertEquals(3, $shipsNextLocationCalculator->getCurrentSize());
        self::assertEquals('[ B-3 ]', (string) $shipsNextLocationCalculator);
    }
}
