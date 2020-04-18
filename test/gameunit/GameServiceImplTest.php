<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/positioning/Location.php';
require_once 'FakeGameUnit.php';

use Game\Battleship\GameServiceImpl;
use Game\Battleship\Location;
use PHPUnit\Framework\TestCase;

class GameServiceImplTest extends TestCase {

    private $gameService;

    private $fakeFirstGameUnit;

    private $fakeSecondGameUnit;

    protected function setUp(): void {
        $this->gameService = new GameServiceImpl();
        $this->fakeFirstGameUnit = new FakeGameUnit($this->gameService);
        $this->fakeSecondGameUnit = new FakeGameUnit($this->gameService);
    }

    public function testThatSecondPlayerReceivesShot() {
        $this->gameService->setFirstGameUnit($this->fakeFirstGameUnit);
        $this->gameService->setSecondGameUnit($this->fakeSecondGameUnit);
        $this->gameService->makeShotFromSourceToOpponentLocation($this->fakeFirstGameUnit, new Location('A', 1));

        self::assertFalse($this->fakeFirstGameUnit->receivedShot);
        self::assertTrue($this->fakeSecondGameUnit->receivedShot);
    }

    public function testThatFirstPlayerReceivesShot() {
        $this->gameService->setFirstGameUnit($this->fakeFirstGameUnit);
        $this->gameService->setSecondGameUnit($this->fakeSecondGameUnit);
        $this->gameService->makeShotFromSourceToOpponentLocation($this->fakeSecondGameUnit, new Location('A', 1));

        self::assertFalse($this->fakeSecondGameUnit->receivedShot);
        self::assertTrue($this->fakeFirstGameUnit->receivedShot);
    }
}
