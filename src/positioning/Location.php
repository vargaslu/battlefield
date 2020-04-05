<?php


namespace Game\Battleship;

require_once 'ILocation.php';

final class Location implements ILocation {

    private const ASCII_A = 65;

    private const ASCII_Z = 90;

    private $letter;

    private $column;

    function __construct(String $letter, $column) {
        $this->validateLetter($letter);
        $this->letter = strtoupper($letter{0});
        $this->column = $column;
    }

    private function validateLetter($letter) {
        $exceptionMessage = '';
        if (strlen($letter) > 1) {
            $exceptionMessage = 'Letter size should not be bigger as 1';
        } elseif ($this->isLetterValueBetweenAAndZ(strtoupper($letter{0}))) {
            $exceptionMessage = 'Valid letter values should be between A-Z';
        }
        if (strlen($exceptionMessage) > 0) {
            throw new InvalidLocationException($exceptionMessage);
        }
    }

    private function isLetterValueBetweenAAndZ($letter) {
        return ord(strtoupper($letter{0})) < self::ASCII_A || ord(strtoupper($letter{0})) > self::ASCII_Z;
    }

    public static function of(Location $location) {
        return new Location($location->getLetter(), $location->getColumn());
    }

    function getLetter() {
        return $this->letter;
    }

    function getColumn() {
        return $this->column;
    }

    function __toString() {
        return $this->letter . "-" .$this->column;
    }

    public function increaseLetter() {
        $this->letter = chr(ord($this->letter) + 1);
    }

    public function increaseColumn() {
        $this->column++;
    }
}
