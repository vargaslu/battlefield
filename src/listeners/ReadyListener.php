<?php


namespace Game\Battleship;

require_once 'PropertyChangeListener.php';

class ReadyListener implements PropertyChangeListener {

    public const READY = 'ready';

    private $stateUpdater;

    private $readyPlayers;

    public function __construct(StateUpdater $stateUpdater) {
        $this->stateUpdater = $stateUpdater;
    }

    public function fireUpdate($key, $property, $value) {
        if (strcmp($property, self::READY) != 0) {
            return;
        }

        switch ($key) {
            case Constants::POSITIONED_SHIPS:
                $this->handlePositionShipsReady();
                break;
        }
    }

    private function handlePositionShipsReady() : void {
        $this->readyPlayers++;

        $this->changeStateWhenAllPlayersAreReady();

        if ($this->readyPlayers == Constants::$CONFIGURED_HUMAN_PLAYERS) {
            $this->stateUpdater->updateCurrentState(GameStateLoader::loadWaitingForAutomaticActionState(), 'place_ships');
        }
    }

    private function changeStateWhenAllPlayersAreReady(): void {
        if ($this->readyPlayers == Constants::MAX_PLAYERS) {
            if (Utils::getRandomPlayerNumber() === 1) {
                $this->stateUpdater->updateCurrentState(GameStateLoader::loadCallingShotsState());
            } else {
                $this->stateUpdater->updateCurrentState(GameStateLoader::loadWaitingForAutomaticActionState(), 'call_shot');
            }
        }
    }
}