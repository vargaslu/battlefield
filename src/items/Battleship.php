<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Battleship extends Ship {

    public const NAME = 'Battleship';

    private function __construct(ShipLocation $location = null) {
        parent::__construct(self::NAME, 4, $location);
    }

    public static function build(ShipLocation $location) {
        return new Battleship($location);
    }

    public static function buildWithoutLocation() {
        return new Battleship();
    }
}