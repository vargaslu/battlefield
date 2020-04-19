<?php


namespace Game\Battleship;

use InvalidArgumentException;

final class Direction {

    const HORIZONTAL = 0;
    const VERTICAL = 1;

    private function __construct() {
    }

    public static function fromJson($data) {
        return static::validate($data['direction']);
    }

    public static function validate($value) {
        if ($value === 0 || strcmp(strtoupper($value), 'H') == 0){
            return Direction::HORIZONTAL;
        } elseif ($value === 1 || strcmp(strtoupper($value), 'V') == 0) {
            return Direction::VERTICAL;
        } else {
            throw new InvalidArgumentException('Not valid value for direction found {'. $value .
                                               '} only valid values are \'H\' for horizontal and'.
                                               '\'V\' for vertical');
        }
    }
}