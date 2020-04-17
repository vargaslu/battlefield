<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Submarine extends Ship {

    public const NAME = "Submarine";

    public const SIZE = 3;

    private function __construct(ShipLocation $location = null) {
        parent::__construct(self::NAME, self::SIZE, $location);
    }

    public static function build(ShipLocation $location) {
        return new Submarine($location);
    }
}