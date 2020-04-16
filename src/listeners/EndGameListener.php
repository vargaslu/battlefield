<?php


namespace Game\Battleship;


class EndGameListener implements PropertyChangeListener {

    private $stateUpdater;

    public function __construct(StateUpdater $stateUpdater) {
        $this->stateUpdater = $stateUpdater;
    }

    function fireUpdate($obj, $property, $value) {
        if (strcmp($property, 'GAME_OVER') == 0) {
            $this->stateUpdater->updateCurrentState($this->stateUpdater->getEndedGameState());
        }
    }
}