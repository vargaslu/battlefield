<?php


namespace Game\Battleship;

require_once __DIR__.'/../../api/game/GameController.php';
require_once __DIR__.'/../listeners/ReadyListener.php';
require_once __DIR__.'/../states/CallingShotsState.php';
require_once __DIR__.'/../states/GameStateLoader.php';
require_once __DIR__.'/../states/StateUpdater.php';
require_once __DIR__.'/../states/WaitingForStartState.php';
require_once 'GameUnit.php';
require_once 'GameServiceImpl.php';
require_once 'PlayerEmulator.php';
require_once 'SuccessfulMessageResult.php';
require_once 'ExceptionMessageResult.php';

use Exception;

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

    public function start() : MessageResult {
        $gameService = new GameServiceImpl();
        $this->humanGameUnit = new GameUnit($gameService);
        $placingShipsState = GameStateLoader::loadPlacingShipsState();
        $placingShipsState->addPropertyChangeListener($this->readyListener);

        $this->configureComputerBasedOpponent($gameService);

        $this->updateCurrentState($placingShipsState);

        return new SuccessfulMessageResult('Game started successfully');
    }

    public function updateCurrentState(GameState $gameState, $value = null) : void {
        $this->gameState = $gameState;
        $gameState->enter($value);
    }

    public function getCurrentState() : GameState {
        return $this->gameState;
    }

    public function placeShip($jsonData) {
        try {
            $ship = ShipFactory::fromJson($jsonData);
            $this->gameState->placingShips($this->humanGameUnit, $ship);
            return new SuccessfulMessageResult('Ship placed successfully');
        } catch (Exception $exception) {
            return new ExceptionMessageResult($exception->getMessage());
        }
    }

    public function callShot($jsonData) {
        $location = Location::fromJson($jsonData);
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