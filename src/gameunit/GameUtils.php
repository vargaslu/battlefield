<?php


namespace Game\Battleship;


class GameUtils {

    public function getRandomLocation() : Location {
        $maxGridSize = Grid::getSize() - 1;
        $column = rand(1, $maxGridSize);
        $letter = chr(rand(Location::ASCII_A, Location::ASCII_A + $maxGridSize));
        return new Location($letter, $column);
    }

    public function getRandomDirection() {
        return rand(Direction::HORIZONTAL, Direction::VERTICAL);
    }
}