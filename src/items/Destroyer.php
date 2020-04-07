<?php

namespace Game\Battleship;

require_once 'Ship.php';

final class Destroyer extends Ship {

    public const NAME = 'Destroyer';

    private function __construct(Location $location, $direction) {
        parent::__construct(self::NAME, 2, $location, $direction);
    }

    public static function build(Location $location, $direction) {
        return new Destroyer($location, $direction);
    }
}