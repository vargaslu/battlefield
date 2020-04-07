<?php


namespace Game\Battleship;

require_once 'GameState.php';

class PlacingShipsState implements GameState {

    private $originalShipsToPlace;

    private $shipsToPlace;

    private $gameController;

    private $current;

    private $next;

    public function __construct(GameController $gameController, GameUnit $current, GameUnit $next) {
        $this->gameController = $gameController;
        $this->current = $current;
        $this->next = $next;
        $this->shipsToPlace = [Carrier::NAME, Destroyer::NAME, Submarine::NAME, Battleship::NAME];
        $this->originalShipsToPlace = $this->shipsToPlace;
    }

    function setShipsToPlace($shipsToPlace) {
        $this->shipsToPlace = $shipsToPlace;
        $this->originalShipsToPlace = $shipsToPlace;
    }

    function placingShips(Ship $ship) {
        if (!$this->isShipInArray($ship->getName(), $this->originalShipsToPlace)) {
            throw new NotAllowedShipException('Ship ' . $ship->getName() . ' not allowed');
        }

        $this->current->placeShip($ship);
        if (($key = array_search($ship->getName(), $this->shipsToPlace)) !== false) {
            unset($this->shipsToPlace[$key]);
        } else {
            throw new NotAllowedShipException('Allowed quantity for ship ' . $ship->getName() . ' already used');
        }

        if (sizeof($this->shipsToPlace) == 0) {
            $this->setNextState(new PlacingShipsState($this->gameController, $this->next, $this->current));
        }
    }

    private function isShipInArray($shipName, $shipArray) {
        return (array_search($shipName, $shipArray)) !== false;
    }

    function callingShot(Location $location) {
        // TODO: Error not calling Shots yet
    }

    function setNextState(GameState $nextGameState) {
        $this->gameController->setState($nextGameState);
    }
}