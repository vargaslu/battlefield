<?php

namespace Game\Battleship;

require_once 'Ship.php';

final class Destroyer extends Ship {

    public const NAME = 'Destroyer';

    private function __construct(ShipLocation $location = null) {
        parent::__construct(self::NAME, 2, $location);
    }

    public static function build(ShipLocation $location) {
        return new Destroyer($location);
    }

    public static function buildWithoutLocation() {
        return new Destroyer();
    }
}