<?php


namespace battleship\gameunit;

require_once 'ILocation.php';

final class LocationAsInteger implements ILocation {

    private const ASCII_DECIMALS_GAP = 64;

    private $location;

    public function __construct(Location $location) {
        $this->location = $location;
    }

    public function of($column, $row) {
        return new LocationAsInteger(new Location(chr($column + self::ASCII_DECIMALS_GAP), $row));
    }

    function getColumn() {
        return ord($this->location->getColumn()) - LocationAsInteger::ASCII_DECIMALS_GAP;
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