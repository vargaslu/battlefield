<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameUnit.php';
require_once __DIR__ . '/../../src/gameunit/Constants.php';
require_once __DIR__ . '/../../src/gameunit/HitResult.php';
require_once __DIR__ . '/../../src/player/PlayerEmulator.php';
require_once __DIR__ . '/../../src/player/LookAroundAttackStrategy.php';
require_once __DIR__ . '/../../src/positioning/Location.php';

require_once __DIR__ . '/../gameunit/FakeGameService.php';
require_once 'FakeLookAroundAttackStrategy.php';

use Game\Battleship\GameUnit;
use Game\Battleship\Grid;
use Game\Battleship\HitResult;
use Game\Battleship\Location;
use Game\Battleship\PropertyChangeListener;
use PHPUnit\Framework\TestCase;

class LookAroundAttackStrategyTest extends TestCase {

    private $fakeGameService;

    private $attackStrategy;

    protected function setUp(): void {
        Grid::setSize(8);
        $this->fakeGameService = new FakeGameService();
        $mockedReadyListener = $this->createStub(PropertyChangeListener::class);
        $gameUnit = new GameUnit($this->fakeGameService);
        $gameUnit->setReadyListener($mockedReadyListener);

        $this->attackStrategy = new FakeLookAroundAttackStrategy($gameUnit);
    }

    public function testMissingShotShouldRandomNextTime() {
        $this->fakeGameService->expectedHitResults([HitResult::createMissedHitResult(),
                                                    HitResult::createMissedHitResult()]);
        $this->attackStrategy->setRandomShotLocations([new Location('A', 1),
                                                       new Location('B', 2)]);

        $this->attackStrategy->makeShot();
        $this->attackStrategy->makeShot();

        self::assertTrue($this->attackStrategy->verifyShot(new Location('A', 1)));
        self::assertTrue($this->attackStrategy->verifyShot(new Location('B', 2)));
    }

    public function testMakeShot() {
        $this->fakeGameService->expectedHitResults([HitResult::createMissedHitResult(),
                                                    HitResult::createSuccessfulHitResult("Carrier"),
                                                    HitResult::createMissedHitResult(),
                                                    HitResult::createMissedHitResult(),
                                                    HitResult::createSuccessfulHitResult("Carrier"),
                                                    HitResult::createSuccessfulHitResult("Carrier")]);

        $this->attackStrategy->setRandomShotLocations([new Location('D', 4),
                                                       new Location('B', 2),
                                                       new Location('C', 4)]);

        $this->attackStrategy->makeShot();
        $this->attackStrategy->makeShot();
        $this->attackStrategy->makeShot();
        $this->attackStrategy->makeShot();
        $this->attackStrategy->makeShot();
        $this->attackStrategy->makeShot();

        self::assertTrue($this->attackStrategy->verifyShot(new Location('D', 4)));
        self::assertTrue($this->attackStrategy->verifyShot(new Location('B', 2)));
        self::assertTrue($this->attackStrategy->verifyShot(new Location('A', 2)));
        self::assertTrue($this->attackStrategy->verifyShot(new Location('B', 1)));
        self::assertTrue($this->attackStrategy->verifyShot(new Location('C', 2)));
        self::assertTrue($this->attackStrategy->verifyShot(new Location('D', 2)));
    }

    public function testBugMakeShotWhenAnotherShipWasFound() {
        $this->fakeGameService->expectedHitResults([HitResult::createSuccessfulHitResult("Destroyer"),
                                                    HitResult::createSuccessfulHitResult("Carrier"),
                                                    HitResult::createSuccessfulHitResult("Destroyer"),
                                                    HitResult::createMissedHitResult(),
                                                    HitResult::createMissedHitResult(),
                                                    HitResult::createSuccessfulHitResult("Carrier")]);

        $this->attackStrategy->setRandomShotLocations([new Location('A', 2)]);

        $this->attackStrategy->makeShot();
        $this->attackStrategy->makeShot();
        $this->attackStrategy->makeShot();

        self::assertTrue($this->attackStrategy->verifyShot(new Location('A', 2)));
        self::assertTrue($this->attackStrategy->verifyShot(new Location('A', 1)));
        self::assertTrue($this->attackStrategy->verifyShot(new Location('B', 2)));
    }
}
