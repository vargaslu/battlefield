<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Battleship extends Ship {

    public const NAME = 'Battleship';

    private function __construct(Location $location, $direction) {
        parent::__construct(self::NAME, 4);
    }

    public static function build(Location $location, $direction) {
        return new Battleship($location, $direction);
    }
}