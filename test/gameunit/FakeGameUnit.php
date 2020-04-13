<?php


namespace Tests\Game\Battleship;

use Game\Battleship\GameService;
use Game\Battleship\GameUnit;
use Game\Battleship\HitResult;
use Game\Battleship\Location;
use Game\Battleship\Ship;

class FakeGameUnit extends GameUnit {

    public $receivedShot;

    public function __construct(GameService $gameService) {
        parent::__construct($gameService);
        $this->receivedShot = false;
    }

    public function placeShip(Ship $ship) {

    }

    public function receiveShot(Location $location) : HitResult{
        $this->receivedShot = true;
        return HitResult::createMissedHitResult();
    }
}