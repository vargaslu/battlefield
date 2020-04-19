<?php


namespace Game\Battleship;

require_once 'GameState.php';

class WaitingForStartState implements GameState {

    private const WAITING_FOR_START_STATE = 'Waiting for start';

    function placingShips(GameUnit $current = null, Ship $ship = null) {
        throw new GameStateException('Not placing ships yet', self::WAITING_FOR_START_STATE);
    }

    function callingShot(GameUnit $current = null,Location $location = null) {
        throw new GameStateException('Not calling shots yet', self::WAITING_FOR_START_STATE);
    }

    public function jsonSerialize() {
        return [ 'state' => self::WAITING_FOR_START_STATE];
    }

    function enter($value = null) {
        // Nothing to do here
    }
}