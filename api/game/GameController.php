<?php


namespace Game\Battleship;


interface GameController {

    function start($data);

    function placeShip($ships);

    function callShot($location);

    function getCurrentState() : GameState;

    function getShipsState();

    function reset() : void;

}