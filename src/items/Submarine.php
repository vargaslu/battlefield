<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Submarine extends Ship {

    public const NAME = "Submarine";

    private function __construct(ShipLocation $location = null) {
        parent::__construct(self::NAME, 3, $location);
    }

    public static function build(ShipLocation $location) {
        return new Submarine($location);
    }

    public static function buildWithoutLocation() {
        return new Submarine();
    }
}