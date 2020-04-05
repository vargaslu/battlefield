<?php


namespace Game\Battleship;

require_once 'ILocation.php';

final class Location implements ILocation {

    private $column;

    private $row;

    function __construct(String $column, $row) {
        $this->validateColumn($column);
        $this->column = strtoupper($column{0});
        $this->row = $row;
    }

    private function validateColumn($column) {
        $exceptionMessage = '';
        if (strlen($column) > 1) {
            $exceptionMessage = 'Column size should not be bigger as 1';
        } elseif (ord(strtoupper($column{0})) < 65 || ord(strtoupper($column{0})) > 90) {
            $exceptionMessage = 'Valid column values should be between A-Z';
        }
        if (strlen($exceptionMessage) > 0) {
            throw new InvalidLocationException($exceptionMessage);
        }
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
