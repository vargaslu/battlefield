<?php


namespace Game\Battleship;


interface GameController {

    function start();

    function placeShip($ship);

    function callShot($location);

    function getCurrentState() : GameState;

    function reset() : void;

}