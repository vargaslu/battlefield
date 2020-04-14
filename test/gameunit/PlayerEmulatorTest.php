<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/PlayerEmulator.php';
require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/gameunit/Constants.php';
require_once 'NotRandomPlayerEmulator.php';

use Game\Battleship\Carrier;
use Game\Battleship\Destroyer;
use Game\Battleship\Constants;
use Game\Battleship\Direction;
use Game\Battleship\GameService;
use Game\Battleship\GameUnit;
use Game\Battleship\HitResult;
use Game\Battleship\Location;
use Game\Battleship\ShipLocation;
use Game\Battleship\PropertyChangeListener;
use PHPUnit\Framework\TestCase;

class PlayerEmulatorTest extends TestCase {

    private $playerEmulator;

    private $gameUnit;

    private $mockedGameService;

    protected function setUp(): void {
        $this->mockedGameService = $this->getMockBuilder(GameService::class)->getMock();
        $this->gameUnit = new GameUnit($this->mockedGameService);

        Constants::$DEFAULT_SHIPS_TO_PLACE = [Carrier::NAME, Destroyer::NAME];

        $this->playerEmulator = new NotRandomPlayerEmulator($this->gameUnit);
    }

    public function testPlacingWithRandomLocations() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->playerEmulator->addPropertyChangeListener($listener)->placeShips();
    }

    public function testPlacingWithoutRandomLocations() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->playerEmulator->disableOriginalRandomCalculations();
        $this->playerEmulator->setDesiredShipLocations([new ShipLocation('A', 1, Direction::VERTICAL),
                                                    new ShipLocation('A', 2, Direction::VERTICAL)]);

        $this->playerEmulator->addPropertyChangeListener($listener)->placeShips();
    }

    public function testSearchAnotherPlaceIfExceptionIsCatch() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->playerEmulator->disableOriginalRandomCalculations();
        $this->playerEmulator->setDesiredShipLocations([new ShipLocation('A', 1, Direction::VERTICAL),
                                                    new ShipLocation('B', 1, Direction::VERTICAL),
                                                    new ShipLocation('A', 2, Direction::VERTICAL)]);

        $this->playerEmulator->addPropertyChangeListener($listener)->placeShips();
    }

    public function testMakeShot() {
        $this->expectedGameServiceResultAtIndex(0, HitResult::createMissedHitResult());
        $this->expectedGameServiceResultAtIndex(1, HitResult::createSuccessfulHitResult("Carrier"));
        $this->expectedGameServiceResultAtIndex(2, HitResult::createMissedHitResult());
        $this->expectedGameServiceResultAtIndex(3, HitResult::createMissedHitResult());
        $this->expectedGameServiceResultAtIndex(4, HitResult::createSuccessfulHitResult("Carrier"));
        $this->expectedGameServiceResultAtIndex(4, HitResult::createSuccessfulHitResult("Carrier"));

        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->any())->method('fireUpdate');

        $this->playerEmulator->disableOriginalRandomCalculations();
        $this->playerEmulator->setDesiredLocations([new Location('D', 4),
                                                    new Location('B', 2),
                                                    new Location('C', 4)]);

        $this->playerEmulator->addPropertyChangeListener($listener);
        $this->playerEmulator->makeShot();
        $this->playerEmulator->makeShot();
        $this->playerEmulator->makeShot();
        $this->playerEmulator->makeShot();
        $this->playerEmulator->makeShot();
        $this->playerEmulator->makeShot();

        self::assertTrue($this->playerEmulator->verifyShot(new Location('D', 4)));
        self::assertTrue($this->playerEmulator->verifyShot(new Location('B', 2)));
        self::assertTrue($this->playerEmulator->verifyShot(new Location('A', 2)));
        self::assertTrue($this->playerEmulator->verifyShot(new Location('B', 1)));
        self::assertTrue($this->playerEmulator->verifyShot(new Location('C', 2)));
        self::assertTrue($this->playerEmulator->verifyShot(new Location('D', 2)));
    }

    public function testMissingShotShouldRandomNextTime() {
        $this->expectedGameServiceResultAtIndex(0, HitResult::createMissedHitResult());
        $this->expectedGameServiceResultAtIndex(1, HitResult::createMissedHitResult());

        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->atMost(2))->method('fireUpdate');

        $this->playerEmulator->disableOriginalRandomCalculations();
        $this->playerEmulator->setDesiredLocations([new Location('A', 1),
                                                    new Location('B', 1)]);

        $this->playerEmulator->addPropertyChangeListener($listener);
        $this->playerEmulator->makeShot();
        $this->playerEmulator->makeShot();
    }

    private function expectedGameServiceResultAtIndex($index, HitResult $hitResult) {
        $this->mockedGameService->expects($this->at($index))
                                ->method('makeShot')
                                ->with($this->gameUnit, $this->anything())
                                ->willReturn($hitResult);
    }
}
