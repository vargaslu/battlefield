<?php


namespace Game\Battleship;

require_once __DIR__.'/../../src/exceptions/InvalidLocationException.php';
require_once __DIR__.'/../../src/gameunit/Grid.php';

final class LocationUtils {

    public const ASCII_A = 65;

    private const ASCII_Z = 90;

    public const ASCII_DECIMALS_GAP = 64;

    public static function validateLetter($letter) {
        $exceptionMessage = '';
        if (strlen($letter) > 1) {
            $exceptionMessage = 'Letter size should not be bigger as 1';
        } elseif (static::isLetterValueBetweenAAndZ(strtoupper($letter{0}))) {
            $exceptionMessage = 'Valid letter values should be between A-Z';
        }
        if (strlen($exceptionMessage) > 0) {
            throw new InvalidLocationException($exceptionMessage);
        }
    }

    public static function validateColumn($column) {
        if ($column <= 0) {
            throw new InvalidLocationException('Column values should be bigger than 1');
        }
    }

    private static function isLetterValueBetweenAAndZ($letter) {
        return ord(strtoupper($letter{0})) < self::ASCII_A || ord(strtoupper($letter{0})) > self::ASCII_Z;
    }

    public static function increase(Location $location, $direction) : Location {
        Direction::validate($direction);

        $letter = $location->getLetter();
        $column = $location->getColumn();
        if ($direction === Direction::VERTICAL) {
            static::increaseLetter($letter);
        } else {
            static::increaseColumn($column);
        }
        return new Location($letter, $column);
    }

    public static function decrease(Location $location, $direction) : Location {
        Direction::validate($direction);
        $letter = $location->getLetter();
        $column = $location->getColumn();
        if ($direction === Direction::VERTICAL) {
            static::decreaseLetter($letter);
        } else {
            static::decreaseColumn($column);
        }
        return new Location($letter, $column);
    }

    public static function validate(Location $location) {
        self::validateLetter($location->getLetter());
        self::validateColumn($location->getColumn());
        self::validateBoundaries($location);
    }

    private static function validateBoundaries(Location $location) {
        if (self::isLocationOutsideGrid($location)) {
            throw new LocationOutOfBoundsException($location);
        }
    }

    private static function isLocationOutsideGrid(Location $location) : bool {
        return self::isLetterOutsideTheGrid($location->getLetter()) ||
               self::isColumnOutsideTheGrid($location->getColumn());
    }

    private static function isLetterOutsideTheGrid($letter) : bool {
        return ord($letter) > (Grid::getSize() + Location::ASCII_DECIMALS_GAP);
    }

    private static function isColumnOutsideTheGrid($column) : bool {
        return $column > Grid::getSize();
    }

    private static function increaseLetter(&$letter) {
        $newLetterValue = chr(ord($letter) + 1);
        if (self::isLetterOutsideTheGrid($newLetterValue)) {
            throw new InvalidLocationException('Letter should not exceed board size');
        }
        $letter = $newLetterValue;
    }

    private static function increaseColumn(&$column) {
        $newColumnValue = $column + 1;
        if (self::isColumnOutsideTheGrid($newColumnValue)) {
            throw new InvalidLocationException('Column should not exceed board size');
        }
        $column = $newColumnValue;
    }

    private static function decreaseLetter(&$letter) {
        $newLetterValue = chr(ord($letter) - 1);
        self::validateLetter($newLetterValue);
        $letter = $newLetterValue;
    }

    private static function decreaseColumn(&$column) {
        $newColumnValue = $column - 1;
        self::validateColumn($newColumnValue);
        $column = $newColumnValue;
    }
}