<?php


namespace Game\Battleship;


interface GameState {

    function placingShips(GameUnit $currentGameUnit);

    function makingShot(GameUnit $currentGameUnit);

    function setNextState(GameState $nextGameState);

}