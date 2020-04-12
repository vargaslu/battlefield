<?php


namespace Game\Battleship;

require_once __DIR__.'/../../api/game/GameController.php';
require_once __DIR__.'/../listeners/ReadyListener.php';
require_once __DIR__.'/../states/CallingShotsState.php';
require_once __DIR__.'/../states/PlacingShipsState.php';
require_once __DIR__.'/../states/StateUpdater.php';
require_once __DIR__.'/../states/WaitingForStartState.php';
require_once __DIR__.'/../states/WaitingForAutomaticActionState.php';
require_once 'GameUnit.php';
require_once 'GameServiceImpl.php';
require_once 'PlayerEmulator.php';

class GameControllerImpl implements GameController, StateUpdater {

    private $gameState;

    private $humanGameUnit;

    private $type;

    private $placedShipsHumanPlayer;

    private $readyListener;

    private $playerEmulator;

    private $waitingForStartState;

    private $placingShipsState;

    private $waitingForAutomaticActionState;

    private $callingShotsState;

    public function __construct() {
        $this->initialize();
    }

    public function start() {
        $gameService = new GameServiceImpl();
        $this->humanGameUnit = new GameUnit($gameService);

        $this->placingShipsState->addPropertyChangeListener($this->readyListener);
        $this->callingShotsState->addPropertyChangeListener($this->readyListener);

        $this->configureComputerBasedOpponent($gameService);

        $this->updateCurrentState($this->placingShipsState);
    }

    public function updateCurrentState(GameState $gameState, $value = null) : void {
        $this->gameState = $gameState;
        $gameState->enter($value);
    }

    public function getCurrentState() : GameState {
        return $this->gameState;
    }

    public function placeShip($jsonData) {
        $ship = ShipFactory::fromJson($jsonData);
        $this->gameState->placingShips($this->humanGameUnit, $ship);
    }

    public function callShot($jsonData) : HitResult {
        $location = Location::fromJson($jsonData);
        return $this->gameState->callingShot($this->humanGameUnit, $location);
    }

    private function configureComputerBasedOpponent(GameServiceImpl $gameService): void {
        if (Constants::$CONFIGURED_HUMAN_PLAYERS == 1) {
            $computerGameUnit = new GameUnit($gameService);
            $this->playerEmulator = new PlayerEmulator($computerGameUnit);
            $this->playerEmulator->addPropertyChangeListener($this->readyListener);

            $this->waitingForAutomaticActionState->setPlayerEmulator($this->playerEmulator);
        }
    }

    public function getWaitingForStartState(): WaitingForStartState {
        return $this->waitingForStartState;
    }

    public function getPlacingShipsState() : PlacingShipsState {
        return $this->placingShipsState;
    }

    public function getWaitingForAutomaticActionState() : WaitingForAutomaticActionState {
        return $this->waitingForAutomaticActionState;
    }

    public function getCallingShotsState() : CallingShotsState {
        return $this->callingShotsState;
    }

    private function initialize() {
        $this->readyListener = new ReadyListener($this);
        $this->waitingForStartState = new WaitingForStartState();
        $this->placingShipsState = new PlacingShipsState();
        $this->waitingForAutomaticActionState = new WaitingForAutomaticActionState();
        $this->callingShotsState = new CallingShotsState();

        $this->updateCurrentState($this->waitingForStartState);
    }

    public function reset(): void {
        $this->initialize();
    }
}