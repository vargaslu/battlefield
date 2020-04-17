<?php


namespace Game\Battleship;


class EndGameListener implements PropertyChangeListener {

    private $stateUpdater;

    public function __construct(StateUpdater $stateUpdater) {
        $this->stateUpdater = $stateUpdater;
    }

    function fireUpdate($obj, $property, $value) {
        error_log('fireUpdate: ' . $property);
        if (strcmp($property, 'GAME_OVER') == 0) {
            error_log('Updating current status');
            $this->stateUpdater->updateCurrentState($this->stateUpdater->getEndedGameState(), $value);
        }
    }
}