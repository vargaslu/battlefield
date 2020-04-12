<?php


namespace Game\Battleship;

use InvalidArgumentException;

final class Direction {

    const HORIZONTAL = 0;
    const VERTICAL = 1;

    private function __construct() {
    }

    public static function fromJson($data) {
        if ($data['direction'] === 0 || strcmp(strtoupper($data['direction']), 'H') == 0){
            return Direction::HORIZONTAL;
        } elseif ($data['direction'] === 1 || strcmp(strtoupper($data['direction']), 'V') == 0) {
            return Direction::VERTICAL;
        } else {
            throw new InvalidArgumentException('Not valid value for direction found {'. $data['direction'] .
                                               '} only valid values are [0, \'H\'] for horizontal -'.
                                               '[1, \'V\'] for vertical');
        }
    }
}