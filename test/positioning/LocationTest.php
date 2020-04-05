<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/positioning/Location.php';
require_once __DIR__ . '/../../src/exceptions/InvalidLocationException.php';

use Game\Battleship\InvalidLocationException;
use Game\Battleship\Location;
use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase {

    public function testInvalidLetterSize() {
        $this->expectException(InvalidLocationException::class);
        new Location("ABC" , 1);
    }

    public function testInvalidLetterValue() {
        $this->expectException(InvalidLocationException::class);
        new Location("1" , 1);
    }

    /**
     * @dataProvider validLetterValues
     */
    public function testValidLetterValue($letter) {
        $location = new Location($letter , 1);
        $this->assertEquals($letter, $location->getLetter());
    }

    public function validLetterValues() {
        return [['A'],['B'],['C'],['D'],['E'],['F'],['G'],['H'],['I'],['J'],['K'],['L'],['M'],['N'],['O'],['P'],['Q'],
        ['R'],['S'],['T'],['U'],['V'],['W'],['X'],['Y'],['Z']];
    }

    public function testLocationCreation() {
        $location = new Location("A" , 1);
        self::assertEquals("A", $location->getLetter());
        self::assertEquals(1, $location->getColumn());
    }

    public function testUppercaseOfLocationCreationLetter() {
        $location = new Location("b" , 2);
        self::assertEquals("B", $location->getLetter());
        self::assertEquals(2, $location->getColumn());
    }

    public function testLocationToString() {
        $location = new Location("c" , 3);
        self::assertEquals("C-3", $location);
    }

    public function testIncreaseLetter() {
        $location = new Location("D" , 3);
        $location->increaseLetter();
        self::assertEquals("E-3", $location);
    }

    public function testIncreaseColumn() {
        $location = new Location("D" , 3);
        $location->increaseColumn();
        self::assertEquals("D-4", $location);
    }

    public function testCopyCreation() {
        $location = new Location("D" , 3);
        $copiedLocation = Location::of($location);
        $copiedLocation->increaseLetter();
        $copiedLocation->increaseColumn();

        self::assertNotEquals($location->getLetter(), $copiedLocation->getLetter());
        self::assertNotEquals($location->getColumn(), $copiedLocation->getColumn());
    }
}
