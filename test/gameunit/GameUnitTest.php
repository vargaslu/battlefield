<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/gameunit/HitResult.php';
require_once __DIR__.'/../items/FakeShip.php';

use Game\Battleship\Direction;
use Game\Battleship\GameUnit;
use Game\Battleship\Grid;
use Game\Battleship\Location;
use PHPUnit\Framework\TestCase;

class GameUnitTest extends TestCase {

    private $fakeShip1;

    protected function setUp(): void {
        Grid::setSize(5);
        $this->fakeShip1 = new FakeShip('FakeShip1', 3);
    }

    public function testConstruction() {
        $gameUnit = new GameUnit();
        self::assertEquals(5, $gameUnit->availableShips());
    }

    public function testPositionSuccessfully() {
        $this->expectNotToPerformAssertions();
        $gameUnit = new GameUnit();
        $gameUnit->placeShip($this->fakeShip1, new Location('A', 1), Direction::VERTICAL);
    }

    public function testVerifyShipWasHit() {
        $gameUnit = new GameUnit();
        $gameUnit->placeShip($this->fakeShip1, new Location('B', 2), Direction::VERTICAL);
        $hitResult = $gameUnit->verifyShot(new Location('B', 2));

        self::assertEquals(true, $hitResult->isHit());
        self::assertEquals('FakeShip1', $hitResult->getShipName());
    }

    public function testVerifyShipWasNotHit() {
        $gameUnit = new GameUnit();
        $gameUnit->placeShip($this->fakeShip1, new Location('B', 2), Direction::VERTICAL);
        $hitResult = $gameUnit->verifyShot(new Location('B', 1));

        self::assertEquals(false, $hitResult->isHit());
        self::assertEquals('', $hitResult->getShipName());
    }
}
