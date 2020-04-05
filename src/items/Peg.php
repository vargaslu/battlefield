<?php


namespace Game\Battleship;

require_once 'Item.php';

class Peg implements Item {

    public const WHITE = 'White';

    public const RED = 'Red';

    private $name;

    private $size;

    private function __construct($name) {
        $this->name = $name;
        $this->size = 1;
    }

    public static function createWhitePeg() {
        return new Peg(self::WHITE);
    }

    public static function createRedPeg() {
        return new Peg(self::RED);
    }

    final function getName() {
        return $this->name;
    }

    final function getSize() {
        return $this->size;
    }
}