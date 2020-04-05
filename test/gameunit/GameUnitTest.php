<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/gameunit/GameService.php';
require_once __DIR__ . '/../../src/gameunit/HitResult.php';
require_once __DIR__.'/../items/FakeShip.php';

use Game\Battleship\Direction;
use Game\Battleship\GameService;
use Game\Battleship\GameUnit;
use Game\Battleship\Grid;
use Game\Battleship\HitResult;
use Game\Battleship\Location;
use PHPUnit\Framework\TestCase;

class GameUnitTest extends TestCase {

    private $fakeShip1;

    private $fakeShip2;

    private $mockedGameService;

    protected function setUp(): void {
        Grid::setSize(5);
        $this->fakeShip1 = new FakeShip('FakeShip1', 3);
        $this->fakeShip2 = new FakeShip('FakeShip2', 2);
        $this->mockedGameService = $this->getMockBuilder(GameService::class)->getMock();
    }

    public function testConstruction() {
        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($this->fakeShip1, new Location('A', 1), Direction::VERTICAL);
        $gameUnit->placeShip($this->fakeShip2, new Location('A', 2), Direction::HORIZONTAL);

        self::assertEquals(2, $gameUnit->availableShips());
    }

    public function testPositionSuccessfully() {
        $this->expectNotToPerformAssertions();
        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($this->fakeShip1, new Location('A', 1), Direction::VERTICAL);
    }

    public function testVerifyShipWasHit() {
        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($this->fakeShip1, new Location('B', 2), Direction::VERTICAL);
        $hitResult = $gameUnit->receiveShot(new Location('B', 2));

        self::assertEquals(true, $hitResult->isHit());
        self::assertEquals('FakeShip1', $hitResult->getShipName());
    }

    public function testVerifyShipWasNotHit() {
        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($this->fakeShip1, new Location('B', 2), Direction::VERTICAL);
        $hitResult = $gameUnit->receiveShot(new Location('B', 1));

        self::assertEquals(false, $hitResult->isHit());
        self::assertEquals('', $hitResult->getShipName());
    }

    public function testVerifyShipIsDestroyed() {
        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($this->fakeShip2, new Location('B', 2), Direction::VERTICAL);
        self::assertEquals(1, $gameUnit->availableShips());

        $hitResult = $gameUnit->receiveShot(new Location('B', 2));
        self::assertEquals(true, $hitResult->isHit());
        self::assertEquals(1, $gameUnit->availableShips());

        $hitResult = $gameUnit->receiveShot(new Location('C', 2));
        self::assertEquals(true, $hitResult->isHit());
        self::assertEquals(0, $gameUnit->availableShips());
    }

    public function testSuccessOpponentHit() {
        $gameUnit = new GameUnit($this->mockedGameService);
        $this->mockedGameService
            ->expects($this->once())
            ->method('makeShot')
            ->with($gameUnit, $this->anything())
            ->willReturn(HitResult::createSuccessfulHitResult($this->fakeShip1));

        $gameUnit->makeShot(new Location('A', 1));
        // TODO: Assert Red Peg
    }

    public function testMissedOpponentHit() {
        $gameUnit = new GameUnit($this->mockedGameService);
        $this->mockedGameService
            ->expects($this->once())
            ->method('makeShot')
            ->with($gameUnit, $this->anything())
            ->willReturn(HitResult::createMissedHitResult());

        $gameUnit->makeShot(new Location('A', 1));
        // TODO: Assert White Peg
    }
}
