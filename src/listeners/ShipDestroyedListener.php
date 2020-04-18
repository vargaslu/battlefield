<?php


namespace Game\Battleship;

require_once 'PropertyChangeListener.php';

class ShipDestroyedListener implements PropertyChangeListener {

    private $ships;

    private $endGameListener;

    private $owner;

    public function __construct($placedShips) {
        $this->ships = $placedShips;
    }

    function setEndGameListener($endGameListener): void {
        $this->endGameListener = $endGameListener;
    }

    public function setOwner($owner): void {
        $this->owner = $owner;
    }

    function fireUpdate($ship, $property, $value): void {
        if (strcmp($value, Ship::DESTROYED) == 0) {
            $this->notifyToListenersIfNoMoreShipsAreAvailable();
        }
    }

    private function notifyToListenersIfNoMoreShipsAreAvailable(): void {
        $isAtLeastOneShipAlive = array_reduce($this->ships, $this->isAtLeastOneShipAliveClosure());
        if (!$isAtLeastOneShipAlive) {
            $this->endGameListener->fireUpdate($this, 'GAME_OVER', $this->owner);
        }
    }

    private function isAtLeastOneShipAliveClosure() {
        return function ($carry, Ship $ship) {
            if (!isset($carry)) {
                return $ship->isAlive();
            }
            $carry = $carry || $ship->isAlive();
            return $carry;
        };
    }
}