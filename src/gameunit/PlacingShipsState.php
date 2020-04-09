<?php


namespace Game\Battleship;

require_once 'GameState.php';
require_once 'GameConstants.php';

class PlacingShipsState implements GameState {

    private $originalShipsToPlace;

    private $shipsToPlace;

    private $listener;

    public function __construct() {
        $this->setShipsToPlace(GameConstants::DEFAULT_SHIPS_TO_PLACE);
    }

    function setShipsToPlace($shipsToPlace) {
        $this->originalShipsToPlace = $shipsToPlace;
        $this->shipsToPlace = $shipsToPlace;
    }

    function placingShips(GameUnit $current, Ship $ship) {
        $this->validateShipIsAllowedToBePlaced($ship);

        $current->placeShip($ship);

        if (($key = array_search($ship->getName(), $this->shipsToPlace)) !== false) {
            unset($this->shipsToPlace[$key]);
        }

        if (sizeof($this->shipsToPlace) == 0) {
            $this->listener->fireUpdate($current, 'ready', true);
        }
    }

    final function addPropertyChangeListener(PropertyChangeListener $listener) {
        $this->listener = $listener;
    }

    private function isShipInArray($shipName, $shipArray) {
        return (array_search($shipName, $shipArray)) !== false;
    }

    function callingShot(Location $location) {
        throw new GameStateException('Not calling shots yet');
    }

    private function validateShipIsAllowedToBePlaced(Ship $ship): void {
        if (!$this->isShipInArray($ship->getName(), $this->originalShipsToPlace)) {
            throw new NotAllowedShipException('Ship ' . $ship->getName() . ' not allowed');
        }
    }
}