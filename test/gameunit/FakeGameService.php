<?php


namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/gameunit/GameService.php';

use Game\Battleship\GameService;
use Game\Battleship\GameUnit;
use Game\Battleship\HitResult;
use Game\Battleship\Location;
use PHPUnit\Framework\Assert;

class FakeGameService implements GameService {

    private $hitResults;

    public function __construct() {
    }

    function expectedHitResults($hitResults) {
        $this->hitResults = $hitResults;
    }

    function makeShot(GameUnit $source, Location $location): HitResult {
        if (sizeof($this->hitResults) === 0) {
            Assert::fail('A configured ShipLocation is missing');
        }

        $hitResult = $this->hitResults[0];
        unset($this->hitResults[0]);
        $this->hitResults = array_values($this->hitResults);
        return $hitResult;
    }
}