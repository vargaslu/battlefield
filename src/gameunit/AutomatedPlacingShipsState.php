<?php


namespace Game\Battleship;

require_once 'GameState.php';

class AutomatedPlacingShipsState implements GameState {

    public function __construct(GameController $gameController, GameUnit $current, GameUnit $opponent) {
        //parent::__construct($gameController, $current, $opponent);
    }

    function placingShips(Ship $ship) {
        // TODO: Message! User should not do nothing, Computer is placing ships
    }

    function callingShot(Location $location) {
        // TODO: Message! User should not do nothing, Computer is placing ships
    }
}