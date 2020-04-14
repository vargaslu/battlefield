<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Cruiser extends Ship {

    public const NAME = 'Cruiser';

    public const SIZE = 3;

    private function __construct(ShipLocation $location) {
        parent::__construct(self::NAME, self::SIZE, $location);
    }

    public static function build(ShipLocation $location) {
        return new Cruiser($location);
    }

    public static function buildWithoutLocation() {
        return new Cruiser();
    }
}