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
        $this->playerEmulator->setDesiredLocations([new Location('A', 1),
                                                    new Location('A', 2)]);
        $this->playerEmulator->setDesiredDirections([Direction::VERTICAL,
                                                     Direction::VERTICAL]);

        $this->playerEmulator->addPropertyChangeListener($listener)->placeShips();
    }

    public function testSearchAnotherPlaceIfExceptionIsCatch() {
        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->playerEmulator->disableOriginalRandomCalculations();
        $this->playerEmulator->setDesiredLocations([new Location('A', 1),
                                                    new Location('B', 1),
                                                    new Location('A', 2)]);
        $this->playerEmulator->setDesiredDirections([Direction::VERTICAL,
                                                     Direction::VERTICAL,
                                                     Direction::VERTICAL]);

        $this->playerEmulator->addPropertyChangeListener($listener)->placeShips();
    }

    public function testMakeShot() {
        $this->mockedGameService->expects($this->at(0))
                                ->method('makeShot')
                                ->with($this->gameUnit, $this->anything())
                                ->willReturn(HitResult::createSuccessfulHitResult("Carrier"));

        $listener = $this->getMockBuilder(PropertyChangeListener::class)->getMock();
        $listener->expects($this->once())->method('fireUpdate');

        $this->playerEmulator->disableOriginalRandomCalculations();
        $this->playerEmulator->setDesiredLocations([new Location('A', 1),
                                                    new Location('B', 1),
                                                    new Location('A', 2)]);
        $this->playerEmulator->addPropertyChangeListener($listener)->makeShot();
    }
}
