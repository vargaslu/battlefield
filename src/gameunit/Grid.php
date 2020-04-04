<?php

namespace battleship\gameunit;

require_once 'Location.php';
require_once 'LocationAsInteger.php';

use battleship\exceptions\InvalidLocationException;
use battleship\exceptions\LocationException;

class Grid {

    private static $size = 8;

    private $board;

    static function setSize($size) {
        Grid::$size = $size;
    }

    function __construct() {
        $this->initializeBoard();
    }

    private function initializeBoard() {
        for ($column = 1; $column <= Grid::$size; $column++) {
            for ($row = 1; $row <= Grid::$size; $row++) {
                $this->board[$column][$row] = "";
            }
        }
    }

    function put(String $item, Location $location) {

        $locationAsInteger = new LocationAsInteger($location);

        if ($this->isLocationOutsideGrid($locationAsInteger)) {
            throw new LocationException("Item: " . $item ." is out of board");
        }

        if ($this->isLocationOccupied($locationAsInteger)) {
            throw new LocationException("Location " .
                $locationAsInteger .
                " is occupied by Item: " .
                $this->getItemFromLocation($locationAsInteger));
        }

        $column = $locationAsInteger->getColumn();
        $row = $locationAsInteger->getRow();

        $this->board[$column][$row] = $item;
    }

    private function isLocationOutsideGrid(ILocation $location) {
        return $location->getColumn() > Grid::$size || $location->getRow() > Grid::$size;
    }

    private function isLocationOccupied(ILocation $location) {
        return $this->getItemFromLocation($location) != "";
    }

    private function getItemFromLocation(ILocation $location) {
        return $this->board[$location->getColumn()][$location->getRow()];
    }

    function asString() {
        $gridDisplay = "";
        for ($row = 1; $row <= Grid::$size; $row++) {
            for ($column = 1; $column <= Grid::$size; $column++) {
                $locationAsInteger = LocationAsInteger::of($column, $row);
                $item = $this->getItemFromLocation($locationAsInteger);
                if (strcmp($item, "") == 0) {
                    continue;
                }
                $gridDisplay = $gridDisplay . $item . ":" . $locationAsInteger . " ";
            }
        }
        return chop($gridDisplay);
    }
}