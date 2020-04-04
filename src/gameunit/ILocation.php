<?php


namespace battleship\gameunit;


interface ILocation {

    function getColumn();

    function getRow();

    function __toString();

    function increaseColumn();

    function increaseRow();
}