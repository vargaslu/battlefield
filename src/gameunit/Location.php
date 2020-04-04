<?php


namespace battleship\gameunit;

require_once 'ILocation.php';

final class Location implements ILocation {

    private $column;

    private $row;

    function __construct(String $column, $row) {
        $this->column = strtoupper($column{0});
        $this->row = $row;
    }

    public static function of(Location $location) {
        return new Location($location->getColumn(), $location->getRow());
    }

    function getColumn() {
        return $this->column;
    }

    function getRow() {
        return $this->row;
    }

    function __toString() {
        return $this->column . "-" .$this->row;
    }

    public function increaseColumn() {
        $this->column = chr(ord($this->column) + 1);
    }

    public function increaseRow() {
        $this->row++;
    }
}
