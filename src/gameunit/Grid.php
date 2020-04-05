<?php

namespace Game\Battleship;

require_once __DIR__.'/../positioning/Location.php';
require_once __DIR__.'/../positioning/LocationAsInteger.php';

use Game\Battleship\LocationException;

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
        $this->board = array_fill(1, Grid::$size, array_fill(1, Grid::$size, ''));
    }

    function put(Item $item, Location $location) {

        $itemName = $item->getName();
        $locationAsInteger = new LocationAsInteger($location);

        if ($this->isLocationOutsideGrid($locationAsInteger)) {
            throw new LocationException("Item: " . $itemName ." is out of board");
        }

        if ($this->isLocationOccupied($locationAsInteger)) {
            throw new LocationException("Location " .
                $locationAsInteger .
                " is occupied by Item: " .
                $this->getItemFromLocation($locationAsInteger));
        }

        $letter = $locationAsInteger->getLetter();
        $column = $locationAsInteger->getColumn();

        $this->board[$letter][$column] = $itemName;
    }

    private function isLocationOutsideGrid(ILocation $location) {
        return $location->getLetter() > Grid::$size || $location->getColumn() > Grid::$size;
    }

    private function isLocationOccupied(ILocation $location) {
        return $this->getItemFromLocation($location) != "";
    }

    private function getItemFromLocation(ILocation $location) {
        return $this->board[$location->getLetter()][$location->getColumn()];
    }

    public function getItem(Location $location) {
        $locationAsInteger = new LocationAsInteger($location);
        if ($this->isLocationOutsideGrid($locationAsInteger)) {
            throw new LocationOutOfBoundsException();
        }

        return $this->getItemFromLocation($locationAsInteger);
    }

    public function getFilteredGrid($filter) {
        $arrayFilter = function ($array) use ($filter) {
            return array_filter($array, $filter);
        };
        return array_filter($this->board, $arrayFilter);
    }

    function asString() {
        $gridDisplay = "";
        for ($column = 1; $column <= Grid::$size; $column++) {
            for ($letter = 1; $letter <= Grid::$size; $letter++) {
                $locationAsInteger = LocationAsInteger::of($letter, $column);
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