<?php


namespace Game\Battleship;


class GameController implements PropertyChangeListener {

    private $gameState;

    private $humanGameUnit;

    private $computerGameUnit;

    private $type;

    private $placedShipsHumanPlayer;

    public function __construct() {

    }

    public function start() {
        //$this->humanGameUnit = new GameUnit(//GameService);
        //$this->computerGameUnit = new GameUnit(//GameService);
        // TODO: set state PlacingShipsState with currentPlayer
        // new AutomatedPlacingShipsState($gameController, $computerGameUnit);
    }

    public function setState(GameState $gameState) {
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

    function fireUpdate($obj, $property, $value) {
        // TODO: Implement fireUpdate() method.
    }
}