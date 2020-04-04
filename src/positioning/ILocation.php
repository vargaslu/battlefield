<?php


namespace Game\Battleship;


interface ILocation {

    function getColumn();

    function getRow();

    function __toString();

    function increaseColumn();

    function increaseRow();

}