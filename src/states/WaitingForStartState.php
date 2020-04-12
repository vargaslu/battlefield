<?php


namespace Game\Battleship;

require_once 'GameState.php';

class WaitingForStartState implements GameState {

    function placingShips(GameUnit $current = null, Ship $ship = null) {
        throw new GameStateException('Not placing ships yet - current state is: Waiting for start');
    }

    function callingShot(Location $location = null) {
        throw new GameStateException('Not calling shots yet - current state is: Waiting for start');
    }

    public function jsonSerialize() {
        return [ 'state' => 'Waiting for start' ];
    }

    function enter($value = null) {
        // Nothing to do here
    }
}