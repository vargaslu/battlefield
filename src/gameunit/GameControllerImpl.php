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

    private $readyListener;

    private $endGameListener;

    private $waitingForStartState;

    private $placingShipsState;

    private $waitingForAutomaticActionState;

    private $callingShotsState;

    private $endedGameState;

    public function __construct() {
        $this->initializeGame();
    }

    private function initializeGame() {
        $this->initializeGameStates();
        $this->initializeListeners();
        $this->updateCurrentState($this->waitingForStartState);
    }

    private function initializeGameStates(): void {
        $this->waitingForStartState = new WaitingForStartState();
        $this->placingShipsState = new PlacingShipsState();
        $this->waitingForAutomaticActionState = new WaitingForAutomaticActionState();
        $this->callingShotsState = new CallingShotsState();
        $this->endedGameState = new EndedGameState();
    }

    private function initializeListeners(): void {
        $this->readyListener = new ReadyListener($this);
        $this->readyListener->acceptedGameStates([$this->placingShipsState,
                                                  $this->waitingForAutomaticActionState,
                                                  $this->callingShotsState]);
        $this->endGameListener = new EndGameListener($this);
    }

    public function start() {
        $gameService = new GameServiceImpl();
        $this->configurePlayers($gameService);

        $this->callingShotsState->addPropertyChangeListener($this->readyListener);

        $this->updateCurrentState($this->placingShipsState);
    }

    private function configurePlayers(GameServiceImpl $gameService): void {
        $this->configureHumanPlayer($gameService);
        $this->configureComputerBasedOpponent($gameService);
    }

    private function configureHumanPlayer(GameServiceImpl $gameService): void {
        $this->humanGameUnit = new GameUnit($gameService);
        $this->humanGameUnit->setReadyListener($this->readyListener);
        $this->humanGameUnit->setEndGameListener($this->endGameListener);

        $this->endedGameState->registerFirstGameUnitOwner($this->humanGameUnit->getOwner());

        $gameService->setFirstGameUnit($this->humanGameUnit);
    }

    private function configureComputerBasedOpponent(GameServiceImpl $gameService): void {
        if (Constants::$CONFIGURED_HUMAN_PLAYERS == 1) {
            $computerGameUnit = new GameUnit($gameService);
            $computerGameUnit->setReadyListener($this->readyListener);
            $computerGameUnit->setEndGameListener($this->endGameListener);
            $computerGameUnit->setOwner('Computer');

            $gameService->setSecondGameUnit($computerGameUnit);

            $this->endedGameState->registerSecondGameUnitOwner($computerGameUnit->getOwner());

            $playerEmulator = new PlayerEmulator($computerGameUnit);
            $playerEmulator->setAttackStrategy(new LookAroundAttackStrategy($computerGameUnit));
            $playerEmulator->addPropertyChangeListener($this->readyListener);

            $this->waitingForAutomaticActionState->setPlayerEmulator($playerEmulator);
        }
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

    public function reset(): void {
        $this->initializeGame();
    }
}