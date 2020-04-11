<?php


namespace Game\Battleship;

require_once __DIR__.'/../listeners/ReadyListener.php';
require_once __DIR__.'/../states/CallingShotsState.php';
require_once __DIR__.'/../states/GameStateLoader.php';
require_once __DIR__.'/../states/WaitingForStartState.php';
require_once 'GameServiceImpl.php';

class GameController {

    private $gameState;

    private $humanGameUnit;

    private $computerGameUnit;

    private $type;

    private $placedShipsHumanPlayer;

    private $readyListener;

    public function __construct() {
        $this->readyListener = new ReadyListener($this);
        $this->setState(GameStateLoader::loadWaitingForStartState());
    }

    public function start() {
        $gameService = new GameServiceImpl();
        $this->humanGameUnit = new GameUnit($gameService);
        $placingShipsState = GameStateLoader::loadPlacingShipsState();
        $placingShipsState->addPropertyChangeListener($this->readyListener);

        $this->configureComputerBasedOpponent($gameService);

        $this->setState($placingShipsState);
    }

    public function setState(GameState $gameState, $value = null) : void {
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

    private function configureComputerBasedOpponent(GameServiceImpl $gameService): void {
        if (GameConstants::$CONFIGURED_HUMAN_PLAYERS == 1) {
            $this->computerGameUnit = new GameUnit($gameService);
            $playerEmulator = new PlayerEmulator($this->computerGameUnit);
            $playerEmulator->addPropertyChangeListener($this->readyListener);
            $waitingForAutomaticActionState = GameStateLoader::loadWaitingForAutomaticActionState();
            $waitingForAutomaticActionState->setPlayerEmulator($playerEmulator);
        }
    }
}