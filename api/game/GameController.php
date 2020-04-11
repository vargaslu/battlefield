<?php


namespace Game\Battleship;


interface GameController {

    function start();

    function placeShip(Ship $ship);

    function callShot(Location $location);

    function getCurrentState() : GameState;

}