<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Submarine extends Ship {

    public const NAME = "Submarine";

    private function __construct(Location $location, $direction) {
        parent::__construct(self::NAME, 3, $location, $direction);
    }

    public static function build(Location $location, $direction) {
        return new Submarine($location, $direction);
    }
}