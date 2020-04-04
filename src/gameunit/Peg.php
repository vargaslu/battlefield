<?php


namespace battleship\gameunit;

require_once 'Item.php';

class Peg implements Item {

    private $name;

    private $size;

    public function __construct() {
        $this->name = 'Peg';
        $this->size = 1;
    }

    final function getName() {
        return $this->name;
    }

    final function getSize() {
        return $this->size;
    }
}