<?php


namespace Game\Battleship;

require_once 'GameState.php';

class CallingShotsState implements GameState {

    function placingShips(GameUnit $current, Ship $ship) {
        throw new GameStateException('Not placing ships anymore');
    }

    function callingShot(GameUnit $current, Location $location) {
        return $current->makeShot($location);
    }

    public function jsonSerialize() {
        return [ 'status' => 'Calling for shots' ];
    }

    function enter($value = null) {
        // Nothing to do here
    }
}