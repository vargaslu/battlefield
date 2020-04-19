<?php


namespace Game\Battleship;


class WaitingForAutomaticActionState implements GameState {

    private const WAITING_FOR_OPPONENT_AUTOMATIC_ACTION_STATE = 'Waiting for opponent automatic action';

    private $playerEmulator;

    public function __construct() {
    }

    final function setPlayerEmulator(PlayerEmulator $playerEmulator) : void {
        $this->playerEmulator = $playerEmulator;
    }

    function placingShips(GameUnit $current, Ship $ship) {
        throw new GameStateException('Not accepting placing of ships',
                                     self::WAITING_FOR_OPPONENT_AUTOMATIC_ACTION_STATE);
    }

    function callingShot(GameUnit $current, Location $location) {
        throw new GameStateException('Not accepting calling shots',
                                     self::WAITING_FOR_OPPONENT_AUTOMATIC_ACTION_STATE);
    }

    function enter($value = null) {
        switch ($value) {
            case 'place_ships':
                $this->playerEmulator->placeShips();
                break;
            case 'call_shot':
                $this->playerEmulator->makeShot();
                break;
        }
    }

    public function jsonSerialize() {
        return [ 'state' => self::WAITING_FOR_OPPONENT_AUTOMATIC_ACTION_STATE];
    }
}