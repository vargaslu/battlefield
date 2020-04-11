<?php


namespace Game\Battleship;


class WaitingForAutomaticActionState implements GameState {

    private $playerEmulator;

    public function __construct() {
    }

    final function setPlayerEmulator(PlayerEmulator $playerEmulator) : void {
        $this->playerEmulator = $playerEmulator;
    }

    function placingShips(GameUnit $current, Ship $ship) {
        // TODO: Implement placingShips() method.
    }

    function callingShot(Location $location) {
        // TODO: Implement callingShot() method.
    }

    function enter($value = null) {
        switch ($value) {
            case 'place_ships':
                $this->playerEmulator->placeShips();
                break;
            case 'call_shot':
                // TODO: decide shot location
                break;
        }
    }

    public function jsonSerialize() {
        return [ 'state' => 'Waiting for opponent automatic action' ];
    }
}