<?php


namespace Game\Battleship;

require_once 'ILocation.php';

final class Location implements ILocation {

    private const ASCII_A = 65;

    private const ASCII_Z = 90;

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
        } elseif ($this->isColumnValueBetweenAandZ(strtoupper($column{0}))) {
            $exceptionMessage = 'Valid column values should be between A-Z';
        }
        if (strlen($exceptionMessage) > 0) {
            throw new InvalidLocationException($exceptionMessage);
        }
    }

    private function isColumnValueBetweenAAndZ($column) {
        return ord(strtoupper($column{0})) < self::ASCII_A || ord(strtoupper($column{0})) > self::ASCII_Z;
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
