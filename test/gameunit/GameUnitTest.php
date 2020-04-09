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
use Game\Battleship\NotAllowedShipException;
use PHPUnit\Framework\TestCase;

class GameUnitTest extends TestCase {

    private const FAKE_SHIP1 = 'FakeShip1';

    private const FAKE_SHIP2 = 'FakeShip2';

    private $mockedGameService;

    protected function setUp(): void {
        Grid::setSize(5);
        $this->mockedGameService = $this->getMockBuilder(GameService::class)->getMock();
    }

    public function testPositionSuccessfully() {
        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 3, new Location('A', 1), Direction::VERTICAL);
        $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new Location('A', 2), Direction::HORIZONTAL);

        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($fakeShip1);
        $gameUnit->placeShip($fakeShip2);

        self::assertEquals(2, $gameUnit->availableShips());
    }

    public function testExceptionWhenPlacingSameShip() {
        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 3, new Location('A', 1), Direction::VERTICAL);
        $fakeShip2 = new FakeShip(self::FAKE_SHIP1, 2, new Location('A', 2), Direction::HORIZONTAL);

        $gameUnit = new GameUnit($this->mockedGameService);
        try {
            $gameUnit->placeShip($fakeShip1);
            $gameUnit->placeShip($fakeShip2);
        } catch (NotAllowedShipException $exception) {
            self::assertEquals('Allowed quantity for ship FakeShip1 already used', $exception->getMessage());
            self::assertEquals(1, $gameUnit->availableShips());
            return;
        }
        self::fail('Should have thrown an exception');
    }

    public function testVerifyShipWasHit() {
        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 3, new Location('B', 2), Direction::VERTICAL);

        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($fakeShip1);
        $hitResult = $gameUnit->receiveShot(new Location('B', 2));

        self::assertEquals(true, $hitResult->isHit());
        self::assertEquals(self::FAKE_SHIP1, $hitResult->getShipName());
    }

    public function testVerifyShipWasNotHit() {
        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 3, new Location('B', 2), Direction::VERTICAL);

        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($fakeShip1);
        $hitResult = $gameUnit->receiveShot(new Location('B', 1));

        self::assertEquals(false, $hitResult->isHit());
        self::assertEquals('', $hitResult->getShipName());
    }

    public function testVerifyShipIsDestroyed() {
        $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new Location('B', 2), Direction::VERTICAL);

        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($fakeShip2);
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
            ->willReturn(HitResult::createSuccessfulHitResult(self::FAKE_SHIP1));

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

    public function testIsLocationFree() {
        $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new Location('B', 2), Direction::VERTICAL);
        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($fakeShip2);

        self::assertTrue($gameUnit->isLocationFree(new Location('B', 3)));
    }

    public function testIsLocationOccupied() {
        $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new Location('B', 2), Direction::VERTICAL);
        $gameUnit = new GameUnit($this->mockedGameService);
        $gameUnit->placeShip($fakeShip2);

        self::assertFalse($gameUnit->isLocationFree(new Location('C', 2)));
    }
}
