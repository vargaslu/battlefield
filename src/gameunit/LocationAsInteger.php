<?php


namespace battleship\gameunit;

require_once 'ILocation.php';

final class LocationAsInteger implements ILocation {

    private const ASCII_DECIMAL_TO_REDUCE = 64;

    private $location;

    public function __construct(Location $location) {
        $this->location = $location;
    }

    function getColumn() {
        return ord($this->location->getColumn()) - LocationAsInteger::ASCII_DECIMAL_TO_REDUCE;
    }

    function getRow() {
        return $this->location->getRow();
    }

    function __toString() {
        return $this->location->__toString();
    }

    function increaseColumn() {
        $this->location->increaseColumn();
    }

    function increaseRow() {
        $this->location->increaseRow();
    }
}