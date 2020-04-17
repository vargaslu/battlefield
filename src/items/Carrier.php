<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Carrier extends Ship {

    public const NAME = 'Carrier';

    public const SIZE = 5;

    private function __construct(ShipLocation $location = null) {
        parent::__construct(self::NAME, self::SIZE, $location);;
    }

    public static function build(ShipLocation $location) {
        return new Carrier($location);
    }
}