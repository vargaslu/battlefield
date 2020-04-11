<?php


namespace Game\Battleship;

require_once 'PropertyChangeListener.php';

class ReadyListener implements PropertyChangeListener {

    public const READY = 'ready';

    private $gameController;

    private $readyPlayers;

    public function __construct(GameController $gameController) {
        $this->gameController = $gameController;
    }

    public function fireUpdate($obj, $property, $value) {
        if (strcmp($property, self::READY) == 0) {
            $this->readyPlayers++;
        }

        if ($this->readyPlayers == GameConstants::$MAX_PLAYERS) {
            // TODO: if property = ready is 2 then do shuffle and change state
            $this->gameController->setState(GameStateLoader::loadCallingShotsState());
        }

        if ($this->readyPlayers == GameConstants::$CONFIGURED_HUMAN_PLAYERS) {
            $this->gameController->setState(GameStateLoader::loadWaitingForAutomaticActionState(), 'place_ships');
        }

    }
}