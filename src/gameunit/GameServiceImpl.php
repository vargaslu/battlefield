<?php


namespace Game\Battleship;

require_once 'GameService.php';
require_once 'HitResult.php';

class GameServiceImpl implements GameService {

    function makeShot(GameUnit $source, Location $location) : HitResult{
        return HitResult::createMissedHitResult();
    }
}