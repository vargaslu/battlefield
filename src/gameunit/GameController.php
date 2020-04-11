<?php


namespace Game\Battleship;

require_once __DIR__.'/../listeners/ReadyListener.php';
require_once 'WaitingForStartState.php';
require_once 'CallingShotsState.php';
require_once 'GameServiceImpl.php';
require_once 'GameStateLoader.php';

class GameController {

    private $gameState;

    private $humanGameUnit;

    private $computerGameUnit;

    private $type;

    private $placedShipsHumanPlayer;

    private $readyListener;

    private $wakeUpListener;

    public function __construct() {
        $this->readyListener = new ReadyListener($this);
        $this->setState(GameStateLoader::loadWaitingForStartState());
    }

    public function start() {
        $gameService = new GameServiceImpl();
        $this->humanGameUnit = new GameUnit($gameService);
        $this->computerGameUnit = new GameUnit($gameService);
        $placingShipsState = GameStateLoader::loadPlacingShipsState();
        $placingShipsState->addPropertyChangeListener($this->readyListener);

        $waitingForAutomaticActionState = GameStateLoader::loadWaitingForAutomaticActionState();
        $waitingForAutomaticActionState->setGameUnit($this->computerGameUnit);
        $waitingForAutomaticActionState->addPropertyChangeListener($this->readyListener);

        $this->setState($placingShipsState);
    }

    public function setState(GameState $gameState, $value = null) {
        $this->gameState = $gameState;
        $gameState->enter($value);
    }

    public function getState() : GameState {
        return $this->gameState;
    }

    public function placeShip(Ship $ship) {
        $this->gameState->placingShips($this->humanGameUnit, $ship);
    }

    public function callShot(Location $location) {
        $this->gameState->callingShot($location);
    }
}