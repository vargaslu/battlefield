<?php


namespace Game\Battleship;


class GameUtils {

    public function getRandomLocation() : Location {
        $column = rand(1, Grid::getSize());
        $letter = chr(rand(Location::ASCII_A, Location::ASCII_A + Grid::getSize()));
        return new Location($letter, $column);
    }

    public function getRandomDirection() {
        return rand(Direction::HORIZONTAL, Direction::VERTICAL);
    }
}