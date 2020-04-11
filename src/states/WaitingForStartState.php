<?php


namespace Game\Battleship;

require_once 'GameState.php';

class WaitingForStartState implements GameState {

    function placingShips(GameUnit $current, Ship $ship) {
        // TODO: Implement placingShips() method.
    }

    function callingShot(Location $location) {
        // TODO: Implement callingShot() method.
    }

    public function jsonSerialize() {
        return [ 'state' => 'Waiting for start' ];
    }

    function enter($value = null) {
        // Nothing to do here
    }
}