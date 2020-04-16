<?php


namespace Game\Battleship;


class EndedGameState implements GameState {

    function placingShips(GameUnit $current, Ship $ship) {
        throw new GameStateException('Not calling shots yet');
    }

    function callingShot(GameUnit $current, Location $location) {
        throw new GameStateException('Not calling shots yet');
    }

    function enter($value = null) {
        // Nothing to do... or put winner
    }

    public function jsonSerialize() {
        return [ 'status' => 'Game ended, You won/lost' ];
    }
}