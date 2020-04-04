<?php


namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/items/Item.php';

use Game\Battleship\Item;

final class FakeItem implements Item {

    private $name;

    private $size;

    public function __construct($name, $size) {
        $this->name = $name;
        $this->size = $size;
    }

    final function getName() {
        return $this->name;
    }

    final function getSize() {
        return $this->size;
    }
}