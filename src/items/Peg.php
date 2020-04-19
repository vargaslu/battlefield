<?php


namespace Game\Battleship;

require_once 'Item.php';

class Peg implements Item {

    public const WHITE = 'White';

    public const RED = 'Red';

    private $name;

    private $size;

    private $location;

    private function __construct($name, Location $location) {
        $this->name = $name;
        $this->size = 1;
        $this->location = $location;
    }

    public static function createWhitePeg(Location $location) {
        return new Peg(self::WHITE, $location);
    }

    public static function createRedPeg(Location $location, $shipName) {
        return new Peg($shipName, $location);
    }

    final function getName() {
        return $this->name;
    }

    final function getSize() {
        return $this->size;
    }

    final function getLocation(): Location {
        return $this->location;
    }
}