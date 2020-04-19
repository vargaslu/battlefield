<?php


namespace Game\Battleship;

require_once __DIR__ . '/../exceptions/GameStateException.php';
require_once __DIR__ . '/../gameunit/Constants.php';
require_once 'GameState.php';

class PlacingShipsState implements GameState {

    private const PLACING_SHIPS_STATE = 'Placing ships';

    public function __construct() {
    }

    function placingShips(GameUnit $current, Ship $ship) {
        $this->validateShipIsAllowedToBePlaced($ship);
        $current->placeShip($ship);
    }

    private function validateShipIsAllowedToBePlaced(Ship $ship): void {
        if (!$this->isShipInArray($ship->getName(), Constants::$DEFAULT_SHIPS_TO_PLACE)) {
            throw new NotAllowedShipException('Ship ' . $ship->getName() . ' not allowed');
        }
    }

    private function isShipInArray($shipName, $shipArray) {
        return (array_search($shipName, $shipArray)) !== false;
    }

    function callingShot(GameUnit $current, Location $location) {
        throw new GameStateException('Not calling shots yet', self::PLACING_SHIPS_STATE);
    }

    public function jsonSerialize() {
        return [ 'status' => self::PLACING_SHIPS_STATE];
    }

    function enter($value = null) {
        // Nothing to do here;
    }
}