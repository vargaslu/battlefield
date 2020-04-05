<?php


namespace Game\Battleship;

require_once 'ILocation.php';

final class LocationAsInteger implements ILocation {

    private const ASCII_DECIMALS_GAP = 64;

    private $location;

    public function __construct(Location $location) {
        $this->location = $location;
    }

    public function of($letter, $column) {
        return new LocationAsInteger(new Location(chr($letter + self::ASCII_DECIMALS_GAP), $column));
    }

    function getLetter() {
        return ord($this->location->getLetter()) - LocationAsInteger::ASCII_DECIMALS_GAP;
    }

    function getColumn() {
        return $this->location->getColumn();
    }

    function __toString() {
        return $this->location->__toString();
    }

    function increaseLetter() {
        $this->location->increaseLetter();
    }

    function increaseColumn() {
        $this->location->increaseColumn();
    }
}