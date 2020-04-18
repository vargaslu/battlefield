<?php


namespace Game\Battleship;


class ShipDestroyedListener implements PropertyChangeListener {

    private $ships;

    private $endGameListener;

    private $owner;

    public function __construct(&$placedShips) {
        $this->ships = &$placedShips;
    }

    function setEndGameListener($endGameListener): void {
        $this->endGameListener = $endGameListener;
    }

    public function setOwner($owner): void {
        $this->owner = $owner;
    }

    function fireUpdate($ship, $property, $value): void {
        if (strcmp($value, Ship::DESTROYED) == 0) {
            unset($this->ships[$ship]);
            $this->notifyToListenersIfNoMoreShipsAreAvailable();
        }
    }

    private function notifyToListenersIfNoMoreShipsAreAvailable(): void {
        if (sizeof($this->ships) === 0) {
            $this->endGameListener->fireUpdate($this, 'GAME_OVER', $this->owner);
        }
    }
}