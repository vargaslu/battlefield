<?php


namespace Game\Battleship;

require_once 'GameState.php';
require_once 'GameConstants.php';

class PlacingShipsState implements GameState {

    private $originalShipsToPlace;

    private $shipsToPlace;

    private $listener;

    public function __construct() {
        $this->originalShipsToPlace = GameConstants::$DEFAULT_SHIPS_TO_PLACE;
        $this->shipsToPlace = GameConstants::$DEFAULT_SHIPS_TO_PLACE;
    }

    function placingShips(GameUnit $current, Ship $ship) {
        $this->validateShipIsAllowedToBePlaced($ship);

        $current->placeShip($ship);

        if (($key = array_search($ship->getName(), $this->shipsToPlace)) !== false) {
            unset($this->shipsToPlace[$key]);
        }

        if (sizeof($this->shipsToPlace) == 0) {
            $this->listener->fireUpdate($current, ReadyListener::READY, true);
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

    public function jsonSerialize() {
        return [ 'status' => 'Placing ships' ];
    }

    function enter($value = null) {
        // Nothing to do here;
    }
}