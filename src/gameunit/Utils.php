<?php


namespace Game\Battleship;


final class Utils {

    public static function getRandomLocation() : Location {
        $maxGridSize = Grid::getSize() - 1;
        $column = rand(1, $maxGridSize);
        $letter = chr(rand(Location::ASCII_A, Location::ASCII_A + $maxGridSize));
        return new Location($letter, $column);
    }

    public static function getRandomShipLocation() : ShipLocation {
        $maxGridSize = Grid::getSize() - 1;
        $column = rand(1, $maxGridSize);
        $letter = chr(rand(Location::ASCII_A, Location::ASCII_A + $maxGridSize));
        $direction = rand(Direction::HORIZONTAL, Direction::VERTICAL);
        return new ShipLocation($letter, $column, $direction);
    }

    public static function getRandomPlayerNumber() : int {
        return rand(1, 2);
    }
}