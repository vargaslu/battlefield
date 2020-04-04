<?php


namespace battleship\gameunit;


abstract class Ship {

    private $name;

    private $size;

    protected function __construct($name, $size) {
        $this->name = $name;
        $this->size = $size;
    }

    final function getName() {
        return $this->name;
    }

    final function getSize() {
        return $this->size;
    }

    function __toString() {
        return $this->name;
    }
}