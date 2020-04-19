<?php


namespace Game\Battleship;

require_once 'GameState.php';

class CallingShotsState implements GameState {

    private const CALLING_FOR_SHOTS_STATE = 'Calling for shots';

    function placingShips(GameUnit $current, Ship $ship) {
        throw new GameStateException('Not placing ships anymore', self::CALLING_FOR_SHOTS_STATE);
    }

    function callingShot(GameUnit $current, Location $location) {
        return $current->callShotIntoLocation($location);
    }

    public function jsonSerialize() {
        return [ 'status' => self::CALLING_FOR_SHOTS_STATE];
    }

    function enter($value = null) {
        // Nothing to do here
    }
}