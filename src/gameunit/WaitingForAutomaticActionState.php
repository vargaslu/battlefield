<?php


namespace Game\Battleship;


class WaitingForAutomaticActionState implements GameState {

    private $listener;

    private $gameUnit;

    private $playerEmulator;

    public function __construct() {
        $this->playerEmulator = new PlayerEmulator();
    }

    final function setGameUnit($gameUnit) {
        $this->playerEmulator->setGameUnit($gameUnit);
    }

    final function addPropertyChangeListener(PropertyChangeListener $listener) {
        //$this->listener = $listener;
        $this->playerEmulator->addPropertyChangeListener($listener);
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