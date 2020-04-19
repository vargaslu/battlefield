<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/gameunit/GameService.php';
require_once __DIR__ . '/../../src/gameunit/HitResult.php';
require_once __DIR__ . '/../../src/listeners/EndGameListener.php';
require_once __DIR__.'/../items/FakeShip.php';

use Game\Battleship\Direction;
use Game\Battleship\EndGameListener;
use Game\Battleship\GameService;
use Game\Battleship\GameUnit;
use Game\Battleship\Grid;
use Game\Battleship\HitResult;
use Game\Battleship\Location;
use Game\Battleship\LocationException;
use Game\Battleship\NotAllowedShipException;
use Game\Battleship\PropertyChangeListener;
use Game\Battleship\ShipLocation;
use Game\Battleship\StateUpdater;
use PHPUnit\Framework\TestCase;

class GameUnitTest extends TestCase {

    private const FAKE_SHIP1 = 'FakeShip1';

    private const FAKE_SHIP2 = 'FakeShip2';

    private $mockedGameService;

    private $gameUnit;

    protected function setUp(): void {
        Grid::setSize(5);
        $this->mockedGameService = $this->getMockBuilder(GameService::class)->getMock();
        $mockedReadyListener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $this->gameUnit = new GameUnit($this->mockedGameService);
        $this->gameUnit->setReadyListener($mockedReadyListener);
    }

    public function testPositionSuccessfully() {
        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 3, new ShipLocation('A', 1, Direction::VERTICAL));
        $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new ShipLocation('A', 2, Direction::HORIZONTAL));

        $this->gameUnit->placeShip($fakeShip1);
        $this->gameUnit->placeShip($fakeShip2);

        self::assertEquals(2, $this->gameUnit->availableShips());
    }

    public function testExceptionWhenPlacingSameShip() {
        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 3, new ShipLocation('A', 1, Direction::VERTICAL));
        $fakeShip2 = new FakeShip(self::FAKE_SHIP1, 2, new ShipLocation('A', 2, Direction::HORIZONTAL));

        try {
            $this->gameUnit->placeShip($fakeShip1);
            $this->gameUnit->placeShip($fakeShip2);
        } catch (NotAllowedShipException $exception) {
            self::assertEquals('Ship FakeShip1 already placed', $exception->getMessage());
            self::assertEquals(1, $this->gameUnit->availableShips());
            return;
        }
        self::fail('Should have thrown an exception');
    }

    public function testVerifyShipWasHit() {
        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 3, new ShipLocation('B', 2, Direction::VERTICAL));

        $this->gameUnit->placeShip($fakeShip1);
        $hitResult = $this->gameUnit->receiveShot(new Location('B', 2));

        self::assertEquals(true, $hitResult->isHit());
        self::assertEquals(self::FAKE_SHIP1, $hitResult->getShipName());
    }

    public function testVerifyShipWasNotHit() {
        $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 3, new ShipLocation('B', 2, Direction::VERTICAL));

        $this->gameUnit->placeShip($fakeShip1);
        $hitResult = $this->gameUnit->receiveShot(new Location('B', 1));

        self::assertEquals(false, $hitResult->isHit());
        self::assertEquals('', $hitResult->getShipName());
    }

    public function testVerifyShipIsDestroyed() {
        $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new ShipLocation('B', 2, Direction::VERTICAL));

        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->gameUnit->setEndGameListener($listener);
        $this->gameUnit->placeShip($fakeShip2);
        self::assertEquals(1, $this->gameUnit->availableShips());

        $hitResult = $this->gameUnit->receiveShot(new Location('B', 2));
        self::assertEquals(true, $hitResult->isHit());
        self::assertEquals(1, $this->gameUnit->availableShips());

        $hitResult = $this->gameUnit->receiveShot(new Location('C', 2));
        self::assertEquals(true, $hitResult->isHit());
        self::assertEquals(0, $this->gameUnit->availableShips());
    }

    public function testSuccessOpponentHit() {
        $this->mockedGameService
            ->expects($this->once())
            ->method('makeShotFromSourceToOpponentLocation')
            ->with($this->gameUnit, $this->anything())
            ->willReturn(HitResult::createSuccessfulHitResult(self::FAKE_SHIP1));

        $this->gameUnit->callShotIntoLocation(new Location('A', 1));
    }

    public function testExceptionWhenSameHitLocationIsUsed() {
        $this->expectException(LocationException::class);

        $this->mockedGameService
            ->expects($this->once())
            ->method('makeShotFromSourceToOpponentLocation')
            ->with($this->gameUnit, $this->anything())
            ->willReturn(HitResult::createSuccessfulHitResult(self::FAKE_SHIP1));

        $this->gameUnit->callShotIntoLocation(new Location('A', 1));
        $this->gameUnit->callShotIntoLocation(new Location('A', 1));
    }

    public function testMissedOpponentHit() {
        $this->mockedGameService
            ->expects($this->once())
            ->method('makeShotFromSourceToOpponentLocation')
            ->with($this->gameUnit, $this->anything())
            ->willReturn(HitResult::createMissedHitResult());

        $this->gameUnit->callShotIntoLocation(new Location('A', 1));
    }

    public function testIsLocationFree() {
        $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new ShipLocation('B', 2, Direction::VERTICAL));
        $this->gameUnit->placeShip($fakeShip2);

        self::assertTrue($this->gameUnit->isLocationFree(new Location('B', 3)));
    }

    public function testIsLocationOccupied() {
        $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new ShipLocation('B', 2, Direction::VERTICAL));
        $this->gameUnit->placeShip($fakeShip2);

        self::assertFalse($this->gameUnit->isLocationFree(new Location('C', 2)));
    }

    public function testExceptionWhenPlacingShipWhereAnotherIsFound() {
        try {
            $fakeShip1 = new FakeShip(self::FAKE_SHIP1, 3, new ShipLocation('A', 3, Direction::VERTICAL));
            $fakeShip2 = new FakeShip(self::FAKE_SHIP2, 2, new ShipLocation('A', 2, Direction::HORIZONTAL));

            $this->gameUnit->placeShip($fakeShip1);
            $this->gameUnit->placeShip($fakeShip2);
        } catch (LocationException $exception) {
            self::assertEquals('Location A-3 is occupied by Item: FakeShip1', $exception->getMessage());
            self::assertTrue($this->gameUnit->isLocationFree(new Location('A', 2)));
        }
    }
}
