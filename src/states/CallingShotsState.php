<?php


namespace Game\Battleship;

require_once 'GameState.php';

class CallingShotsState implements GameState {

    private $listener;

    function placingShips(GameUnit $current, Ship $ship) {
        throw new GameStateException('Not placing ships anymore');
    }

    function callingShot(GameUnit $current ,Location $location) {
        $hitResult = $current->makeShot($location);
        // TODO: Basic game is just one shot... here is for the other game a change
        $this->listener->fireUpdate(Constants::CALLED_SHOT, ReadyListener::READY, true);
        return $hitResult;
    }

    final function addPropertyChangeListener(PropertyChangeListener $listener) {
        $this->listener = $listener;
    }

    public function jsonSerialize() {
        return [ 'status' => 'Calling for shots' ];
    }

    function enter($value = null) {
        // Nothing to do here
    }
}