<?php


namespace Game\Battleship;


class GameController {

    private $gameState;

    private $humanPlayer;

    private $computerPlayer;

    private $type;

    private $placedShipsHumanPlayer;

    public function __construct() {

    }

    public function start() {
        //$this->humanPlayer = new GameUnit(//GameService);
        //$this->computerPlayer = new GameUnit(//GameService);
        // TODO: set state PlacingShipsState with currentPlayer
    }

    public function setState(GameState $gameState) {
        $this->gameState = $gameState;
    }

    public function getState() : GameState {
        return $this->gameState;
    }

    public function placeShip(Ship $ship) {
        $this->gameState->placingShips($ship);
    }

    public function callShot(Location $location) {
        $this->gameState->callingShot($location);
    }
}