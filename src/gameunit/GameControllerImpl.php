<?php


namespace Game\Battleship;

require_once __DIR__.'/../../api/game/GameController.php';
require_once __DIR__.'/../listeners/ReadyListener.php';
require_once __DIR__.'/../listeners/EndGameListener.php';
require_once __DIR__.'/../player/PlayerEmulator.php';
require_once __DIR__.'/../player/LookAroundAttackStrategy.php';
require_once __DIR__.'/../positioning/ShipLocation.php';
require_once __DIR__.'/../states/EndedGameState.php';
require_once __DIR__.'/../states/CallingShotsState.php';
require_once __DIR__.'/../states/PlacingShipsState.php';
require_once __DIR__.'/../states/StateUpdater.php';
require_once __DIR__.'/../states/WaitingForStartState.php';
require_once __DIR__.'/../states/WaitingForAutomaticActionState.php';
require_once 'GameUnit.php';
require_once 'GameServiceImpl.php';

class GameControllerImpl implements GameController, StateUpdater {

    private $gameState;

    private $humanGameUnit;

    private $type;

    private $placedShipsHumanPlayer;

    private $readyListener;

    private $endGameListener;

    private $playerEmulator;

    private $waitingForStartState;

    private $placingShipsState;

    private $waitingForAutomaticActionState;

    private $callingShotsState;

    private $endedGameState;

    public function __construct() {
        $this->initialize();
    }

    public function start() {
        $gameService = new GameServiceImpl();
        $this->humanGameUnit = new GameUnit($gameService);
        $this->humanGameUnit->setEndListener($this->endGameListener);

        $gameService->setFirstGameUnit($this->humanGameUnit);

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
            $gameService->setSecondGameUnit($computerGameUnit);

            $playerEmulator = new PlayerEmulator($computerGameUnit);
            $playerEmulator->setAttackStrategy(new LookAroundAttackStrategy($computerGameUnit));
            $playerEmulator->addPropertyChangeListener($this->readyListener);

            $this->waitingForAutomaticActionState->setPlayerEmulator($playerEmulator);
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

    public function getEndedGameState() : EndedGameState {
        return $this->endedGameState;
    }

    public function getShipsState() {
        if (isset($this->humanGameUnit)) {
            return $this->humanGameUnit->getPlacedShips();
        }
        return [];
    }

    private function initialize() {
        $this->readyListener = new ReadyListener($this);
        $this->endGameListener = new EndGameListener($this);

        $this->waitingForStartState = new WaitingForStartState();
        $this->placingShipsState = new PlacingShipsState();
        $this->waitingForAutomaticActionState = new WaitingForAutomaticActionState();
        $this->callingShotsState = new CallingShotsState();
        $this->endedGameState = new EndedGameState();

        $this->updateCurrentState($this->waitingForStartState);
    }

    public function reset(): void {
        $this->initialize();
    }
}