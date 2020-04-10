<?php


namespace Game\Battleship;

require_once __DIR__.'/../listeners/PropertyChangeListener.php';
require_once 'WaitingForStartState.php';

class GameController implements PropertyChangeListener {

    private $gameState;

    private $humanGameUnit;

    private $computerGameUnit;

    private $type;

    private $placedShipsHumanPlayer;

    private $readyPlayers;

    public function __construct() {
        $this->readyPlayers = 0;
        $this->setState(new WaitingForStartState());
    }

    public function start() {
        $gameService = new GameServiceImpl();
        $this->humanGameUnit = new GameUnit($gameService);
        $this->computerGameUnit = new GameUnit($gameService);
        $placingShipsState = new PlacingShipsState();
        $placingShipsState->addPropertyChangeListener($this);

        $this->setState($placingShipsState);

        (new PlayerEmulator($this->computerGameUnit))->addPropertyChangeListener($this)
                                                     ->placeShips();
    }

    protected function setState(GameState $gameState) {
        $this->gameState = $gameState;
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

    public function fireUpdate($obj, $property, $value) {
        if (strcmp($property, 'ready') == 0) {
            $this->readyPlayers++;

        }
        // TODO: if property = ready is 2 then do shuffle and change state
    }
}