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
        throw new GameStateException('Not accepting placing of ships, Please wait until I finish');
    }

    function callingShot(GameUnit $current, Location $location) {
        throw new GameStateException('Not accepting calling shots, Please wait until I finish');
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