<?php


namespace Game\Battleship;

require_once __DIR__.'/../listeners/ReadyListener.php';
require_once __DIR__.'/../states/CallingShotsState.php';
require_once __DIR__.'/../states/GameStateLoader.php';
require_once __DIR__.'/../states/StateUpdater.php';
require_once __DIR__.'/../states/WaitingForStartState.php';
require_once 'GameServiceImpl.php';

class GameControllerImpl implements GameController, StateUpdater {

    private $gameState;

    private $humanGameUnit;

    private $type;

    private $placedShipsHumanPlayer;

    private $readyListener;

    public function __construct() {
        $this->readyListener = new ReadyListener($this);
        $this->updateCurrentState(GameStateLoader::loadWaitingForStartState());
    }

    public function start() {
        $gameService = new GameServiceImpl();
        $this->humanGameUnit = new GameUnit($gameService);
        $placingShipsState = GameStateLoader::loadPlacingShipsState();
        $placingShipsState->addPropertyChangeListener($this->readyListener);

        $this->configureComputerBasedOpponent($gameService);

        $this->updateCurrentState($placingShipsState);
    }

    public function updateCurrentState(GameState $gameState, $value = null) : void {
        $this->gameState = $gameState;
        $gameState->enter($value);
    }

    public function getCurrentState() : GameState {
        return $this->gameState;
    }

    public function placeShip(Ship $ship) {
        $this->gameState->placingShips($this->humanGameUnit, $ship);
    }

    public function callShot(Location $location) {
        $this->gameState->callingShot($location);
    }

    private function configureComputerBasedOpponent(GameServiceImpl $gameService): void {
        if (Constants::$CONFIGURED_HUMAN_PLAYERS == 1) {
            $computerGameUnit = new GameUnit($gameService);
            $playerEmulator = new PlayerEmulator($computerGameUnit);
            $playerEmulator->addPropertyChangeListener($this->readyListener);
            $waitingForAutomaticActionState = GameStateLoader::loadWaitingForAutomaticActionState();
            $waitingForAutomaticActionState->setPlayerEmulator($playerEmulator);
        }
    }
}