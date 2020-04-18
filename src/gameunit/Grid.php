<?php

namespace Game\Battleship;

require_once __DIR__.'/../positioning/Location.php';
require_once __DIR__.'/../exceptions/LocationException.php';
require_once __DIR__.'/../exceptions/LocationOutOfBoundsException.php';

use Game\Battleship\LocationException;
use ArrayObject;

class Grid {

    private static $size = 8;

    private $board;

    static function setSize($size) {
        Grid::$size = $size;
    }

    static function getSize() {
        return Grid::$size;
    }

    function __construct() {
        $this->initializeBoard();
    }

    private function initializeBoard() {
        $letters = [];
        for ($asciiLetter = Location::ASCII_A; $asciiLetter < Grid::$size + Location::ASCII_A; $asciiLetter++) {
            $letters[] = chr($asciiLetter);
        }
        $this->board = array_fill_keys($letters, array_fill(1, Grid::$size, ''));
    }

    function put(Item $item, Location $location) {

        $itemName = $item->getName();

        $this->validateLocation($location);

        $letter = $location->getLetter();
        $column = $location->getColumn();

        $this->board[$letter][$column] = $itemName;
    }

    private function isLocationOutsideGrid(Location $location) : bool {
        return ord($location->getLetter()) > (Grid::$size + Location::ASCII_DECIMALS_GAP) || $location->getColumn() > Grid::$size;
    }

    public function validateLocation(Location $location) {
        if ($this->isLocationOutsideGrid($location)) {
            throw new LocationOutOfBoundsException($location);
        }

        if ($this->isLocationOccupied($location)) {
            throw new LocationException("Location " .
                                        $location .
                                        " is occupied by Item: " .
                                        $this->getItemFromLocation($location));
        }
    }

    private function isLocationOccupied(Location $location) : bool {
        return $this->getItemFromLocation($location) !== '';
    }

    private function getItemFromLocation(Location $location) {
        return $this->board[$location->getLetter()][$location->getColumn()];
    }

    public function getItem(Location $location) {
        if ($this->isLocationOutsideGrid($location)) {
            throw new LocationOutOfBoundsException($location);
        }

        return $this->getItemFromLocation($location);
    }

    public function getFilteredGrid($filterClosure) {
        $copyArrayObject = new ArrayObject($this->board);
        $filteredArray = $copyArrayObject->getArrayCopy();

        foreach ($this->board as $letter => $columns) {
            foreach ($columns as $column => $value) {
                if (!$filterClosure($value)) {
                    unset($filteredArray[$letter][$column]);
                }
            }
            if (sizeof($filteredArray[$letter]) == 0) {
                unset($filteredArray[$letter]);
            }
        }
        return $filteredArray;
    }

    public function getFilteredGridAsArray($filterClosure) {
        $filteredArray = [];

        foreach ($this->board as $letter => $columns) {
            foreach ($columns as $column => $value) {
                if ($filterClosure($value)) {
                    array_push($filteredArray, [$letter, $column]);
                }
            }
        }
        return $filteredArray;
    }

    function asString() {
        $gridDisplay = "";

        foreach ($this->board as $letter => $columns) {
            foreach ($columns as $column => $value) {
                $location = new Location($letter, $column);
                $item = $this->getItemFromLocation($location);
                if (strcmp($item, "") == 0) {
                    continue;
                }
                $gridDisplay = $gridDisplay . $item . ":" . $location . " ";
            }
        }

        return chop($gridDisplay);
    }
}