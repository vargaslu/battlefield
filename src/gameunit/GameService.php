<?php


namespace Game\Battleship;


interface GameService {

    function makeShotFromSourceToOpponentLocation(GameUnit $source, Location $location) : HitResult;

}