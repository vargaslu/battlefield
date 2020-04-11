<?php


namespace Game\Battleship;


interface StateUpdater {

    function updateCurrentState(GameState $gameState, $value = null) : void;

}