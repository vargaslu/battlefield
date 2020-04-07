<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Carrier extends Ship {

    public const NAME = 'Carrier';

    private function __construct(Location $location, $direction) {
        parent::__construct(self::NAME, 5, $location, $direction);;
    }

    public static function build(Location $location, $direction) {
        return new Carrier($location, $direction);
    }
}