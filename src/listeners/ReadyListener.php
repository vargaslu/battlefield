<?php


namespace Game\Battleship;

require_once 'PropertyChangeListener.php';

class ReadyListener implements PropertyChangeListener {

    public const READY = 'ready';

    private $stateUpdater;

    private $readyPlayers;

    private $playerNumber;

    public function __construct(StateUpdater $stateUpdater) {
        $this->stateUpdater = $stateUpdater;
        $this->playerNumber = 0;
    }

    public function fireUpdate($key, $property, $value) {
        if (strcmp($property, self::READY) != 0) {
            return;
        }

        switch ($key) {
            case Constants::POSITIONED_SHIPS:
                $this->handlePositionShipsReady();
                break;
            case Constants::CALLED_SHOT:
                $this->handleCallingShots();
                break;
        }
    }

    private function handlePositionShipsReady() : void {
        $this->readyPlayers++;

        $this->changeStateWhenAllPlayersAreReady();

        if ($this->readyPlayers == Constants::$CONFIGURED_HUMAN_PLAYERS) {
            $this->stateUpdater->updateCurrentState($this->stateUpdater->getWaitingForAutomaticActionState(), 'place_ships');
        }
    }

    private function changeStateWhenAllPlayersAreReady(): void {
        if ($this->readyPlayers == Constants::MAX_PLAYERS) {
            $this->playerNumber = Utils::getRandomPlayerNumber();
            $this->handleCallingShots();
        }
    }

    private function handleCallingShots() {
        if ($this->playerNumber === 1) {
            $this->playerNumber = 2;
            $this->stateUpdater->updateCurrentState($this->stateUpdater->getCallingShotsState());
        } else {
            $this->playerNumber = 1;
            $this->stateUpdater->updateCurrentState($this->stateUpdater->getWaitingForAutomaticActionState(), 'call_shot');
        }
    }


}