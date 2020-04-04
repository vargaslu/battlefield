<?php


namespace battleship\gameunit;

require_once 'Item.php';

abstract class Ship implements Item {

    private $name;

    private $size;

    private $lives;

    protected function __construct($name, $size) {
        $this->name = $name;
        $this->size = $size;
        $this->lives = $size;
    }

    final function getName() {
        return $this->name;
    }

    final function getSize() {
        return $this->size;
    }

    final function hit() {
        $this->lives--;
    }

    final function isAlive() {
        return $this->lives > 0;
    }

    function __toString() {
        return $this->name;
    }
}