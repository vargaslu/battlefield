<?php


namespace Game\Battleship;


interface ILocation {

    function getLetter();

    function getColumn();

    function __toString();

    function increaseLetter();

    function increaseColumn();

}