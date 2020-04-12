<?php


namespace Game\Battleship;


interface StateUpdater {

    function getWaitingForStartState(): WaitingForStartState;

    function getPlacingShipsState() : PlacingShipsState;

    function getWaitingForAutomaticActionState() : WaitingForAutomaticActionState;

    function getCallingShotsState() : CallingShotsState;

    function updateCurrentState(GameState $gameState, $value = null) : void;

}