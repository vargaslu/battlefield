<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Carrier extends Ship {

    public const NAME = 'Carrier';

    private function __construct(ShipLocation $location = null) {
        parent::__construct(self::NAME, 5, $location);;
    }

    public static function build(ShipLocation $location) {
        return new Carrier($location);
    }

    public static function buildWithoutLocation() {
        return new Carrier();
    }
}