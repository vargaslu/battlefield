<?php


namespace Game\Battleship;

require_once __DIR__.'/../items/Ship.php';
require_once __DIR__.'/../positioning/Location.php';
require_once __DIR__ . '/../positioning/Direction.php';
require_once 'Grid.php';

class Ocean {

    private $grid;

    function __construct(Grid $grid) {
        $this->grid = $grid;
    }

    function place(Ship $ship) {
        $this->validateShipLocationFromShip($ship);
        $this->validateGridIsNotOccupiedBeforePlacingShip($ship);

        $this->placeShipInTheGridAccordingToLocationAndSize($ship);
    }

    private function validateShipLocationFromShip(Ship $ship) {
        $locationCopy = clone $ship->getLocation();
        $locationCopy->increase($ship->getSize() - 1);
        if ($this->isLocationOutsideGrid($locationCopy)) {
            throw new LocationOutOfBoundsException($ship->getLocation(), $ship->getName());
        }
    }

    private function validateGridIsNotOccupiedBeforePlacingShip(Ship $ship) {
        $locationCopy = clone $ship->getLocation();
        for ($size = 0; $size < $ship->getSize(); $size++) {
            $this->grid->validateLocation($locationCopy->extractLocation());
            $locationCopy->increase(1);
        }
    }

    private function placeShipInTheGridAccordingToLocationAndSize(Ship $ship): void {
        $locationCopy = clone $ship->getLocation();
        for ($size = 0; $size < $ship->getSize(); $size++) {
            $this->grid->put($ship, $locationCopy);
            $locationCopy->increase(1);
        }
    }

    private function isLocationOutsideGrid(ShipLocation $location) {
        return ord($location->getLetter()) > (Grid::getSize() + Location::ASCII_DECIMALS_GAP) ||
            $location->getColumn() > Grid::getSize();
    }

    function peek(Location $location) {
        return $this->grid->getItem($location);
    }
}