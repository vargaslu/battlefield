<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/player/ShipsNextLocationCalculator.php';
require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/positioning/Location.php';

use Game\Battleship\GameUnit;
use Game\Battleship\Location;
use Game\Battleship\ShipsNextLocationCalculator;
use PHPUnit\Framework\TestCase;

class ShipsNextLocationCalculatorTest extends TestCase {

    const CARRIER = "Carrier";
    private $locationCalculator;

    private $mockedGameUnit;

    protected function setUp(): void {
        $this->mockedGameUnit = $this->createStub(GameUnit::class);
        $this->locationCalculator = new ShipsNextLocationCalculator($this->mockedGameUnit);
    }

    public function testSuccessfulCreateCalculations() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('B', 3), 3);

        self::assertEquals('[ B-3, A-3, B-2, C-3, B-4 ]', (string) $this->locationCalculator);
        self::assertEquals(3, $this->locationCalculator->getCurrentSize());
    }

    public function testCouldNotOverwriteInCreateCalculations() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('B', 3), 3);
        self::assertEquals('[ B-3, A-3, B-2, C-3, B-4 ]', (string) $this->locationCalculator);

        $this->locationCalculator->createCalculations(self::CARRIER, new Location('D', 3), 3);
        self::assertEquals(3, $this->locationCalculator->getCurrentSize());
        self::assertEquals('[ B-3, A-3, B-2, C-3, B-4 ]', (string) $this->locationCalculator);
    }

    public function testCreateCalculationsWithUpperLeftBoundaries() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('A', 1), 3);

        self::assertEquals('[ A-1, B-1, A-2 ]', (string) $this->locationCalculator);
    }

    public function testfulCreateCalculationsWithLowerLeftBoundaries() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('H', 1), 3);

        self::assertEquals('[ H-1, G-1, H-2 ]', (string) $this->locationCalculator);
    }

    public function testCreateCalculationsWithUpperRightBoundaries() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('A', 8), 3);

        self::assertEquals('[ A-8, A-7, B-8 ]', (string) $this->locationCalculator);
    }

    public function testCreateCalculationsWithLowerRightBoundaries() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('H', 8), 3);

        self::assertEquals('[ H-8, G-8, H-7 ]', (string) $this->locationCalculator);
    }

    public function testCreateCalculationsWithAlreadyMarkedLocations() {
        $this->mockedGameUnit->method('isTargetLocationMarked')
                             ->will($this->onConsecutiveCalls(false, true, false, true));
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('C', 3), 3);

        self::assertEquals('[ C-3, B-3, D-3 ]', (string) $this->locationCalculator);
    }

    public function testGetCurrentLocation() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('H', 8), 3);

        self::assertEquals('G-8', (string) $this->locationCalculator->getCurrentLocation());
    }

    public function testRemovalOfCurrentLocation() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('C', 3), 3);

        $this->locationCalculator->removeCurrentLocation();
        self::assertEquals('[ C-3, C-2, D-3, C-4 ]', (string) $this->locationCalculator);
    }

    public function testRecalculationOfLocationsHorizontalLeft() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('C', 3), 3);
        $this->locationCalculator->markHitToShip(self::CARRIER);

        $this->locationCalculator->removeCurrentLocation();
        self::assertEquals('[ C-3, C-2, D-3, C-4 ]', (string) $this->locationCalculator);

        $this->locationCalculator->markHitToShip(self::CARRIER);
        $this->locationCalculator->recalculateNextPossibleLocations();
        self::assertEquals('[ C-3, C-2, C-1 ]', (string) $this->locationCalculator);
    }

    public function testRecalculationOfLocationsHorizontalRight() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('C', 3), 3);
        $this->locationCalculator->markHitToShip(self::CARRIER);

        $this->locationCalculator->removeCurrentLocation();
        $this->locationCalculator->removeCurrentLocation();
        $this->locationCalculator->removeCurrentLocation();
        self::assertEquals('[ C-3, C-4 ]', (string) $this->locationCalculator);

        $this->locationCalculator->markHitToShip(self::CARRIER);
        $this->locationCalculator->recalculateNextPossibleLocations();
        self::assertEquals('[ C-3, C-4, C-5 ]', (string) $this->locationCalculator);
    }

    public function testRecalculationOfLocationsVerticalUp() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('C', 3), 3);
        $this->locationCalculator->markHitToShip(self::CARRIER);

        self::assertEquals('[ C-3, B-3, C-2, D-3, C-4 ]', (string) $this->locationCalculator);

        $this->locationCalculator->markHitToShip(self::CARRIER);
        $this->locationCalculator->recalculateNextPossibleLocations();
        self::assertEquals('[ C-3, B-3, A-3 ]', (string) $this->locationCalculator);
    }

    public function testRecalculationOfLocationsVerticalDown() {
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('C', 3), 3);
        $this->locationCalculator->markHitToShip(self::CARRIER);

        $this->locationCalculator->removeCurrentLocation();
        $this->locationCalculator->removeCurrentLocation();
        self::assertEquals('[ C-3, D-3, C-4 ]', (string) $this->locationCalculator);

        $this->locationCalculator->markHitToShip(self::CARRIER);
        $this->locationCalculator->recalculateNextPossibleLocations();
        self::assertEquals('[ C-3, D-3, E-3 ]', (string) $this->locationCalculator);
    }

    public function testTurnAroundFromHorizontalLeftToRight() {
        $this->mockedGameUnit->method('isTargetLocationMarked')
                             ->will($this->onConsecutiveCalls(false, false, false, false, true, false));
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('C', 3), 3);
        $this->locationCalculator->markHitToShip(self::CARRIER);

        $this->locationCalculator->removeCurrentLocation();
        self::assertEquals('[ C-3, C-2, D-3, C-4 ]', (string) $this->locationCalculator);

        $this->locationCalculator->markHitToShip(self::CARRIER);
        $this->locationCalculator->recalculateNextPossibleLocations();
        self::assertEquals('[ C-3, C-2, C-4 ]', (string) $this->locationCalculator);
    }

    public function testTurnAroundFromHorizontalRightToLeft() {
        $this->mockedGameUnit->method('isTargetLocationMarked')
                             ->will($this->onConsecutiveCalls(false, false, false, false, false, false));
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('C', 7), 4);
        $this->locationCalculator->markHitToShip(self::CARRIER);

        $this->locationCalculator->removeCurrentLocation();
        $this->locationCalculator->removeCurrentLocation();
        $this->locationCalculator->removeCurrentLocation();
        self::assertEquals('[ C-7, C-8 ]', (string) $this->locationCalculator);

        $this->locationCalculator->markHitToShip(self::CARRIER);
        $this->locationCalculator->recalculateNextPossibleLocations();
        self::assertEquals('[ C-7, C-8, C-6, C-5 ]', (string) $this->locationCalculator);
    }

    public function testTurnAroundFromVerticalUpToDown() {
        $this->mockedGameUnit->method('isTargetLocationMarked')
                             ->will($this->onConsecutiveCalls(false, false, false, false, false, false));
        $this->locationCalculator->createCalculations(self::CARRIER, new Location('B', 3), 4);
        $this->locationCalculator->markHitToShip(self::CARRIER);

        self::assertEquals('[ B-3, A-3, B-2, C-3, B-4 ]', (string) $this->locationCalculator);

        $this->locationCalculator->markHitToShip(self::CARRIER);
        $this->locationCalculator->recalculateNextPossibleLocations();
        self::assertEquals('[ B-3, A-3, C-3, D-3 ]', (string) $this->locationCalculator);
    }
}
