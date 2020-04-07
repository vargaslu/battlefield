<?php


namespace Game\Battleship;


class GameController {

    private $gameState;

    private $humanPlayer;

    private $computerPlayer;

    private $type;

    public function __construct() {

    }

    public function start() {
        //$this->humanPlayer = new GameUnit(//GameService);
        //$this->computerPlayer = new GameUnit(//GameService);
        // TODO: set state PlacingShipsState
    }

    public function setState(GameState $gameState) {
        $this->gameState = $gameState;
    }

    public function getState() {
        return $this->gameState;
    }

    public function placeShip(Ship $ship, Location $location) {
        $this->gameState->placingShips();
    }
}