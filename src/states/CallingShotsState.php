<?php


namespace Game\Battleship;

require_once 'GameState.php';

class CallingShotsState implements GameState {

    function placingShips(GameUnit $current, Ship $ship) {
        throw new GameStateException('Not placing ships anymore');
    }

    function callingShot(Location $location) {
        // TODO: Implement callingShot() method.
    }

    public function jsonSerialize() {
        return [ 'status' => 'Calling for shots' ];
    }

    function enter($value = null) {
        // TODO: Implement enter() method.
    }
}