<?php


namespace Game\Battleship;


interface GameController {

    function start($data);

    function placeShip($ships) : PlaceShipResult;

    function callShot($location);

    function getCurrentState() : GameState;

    function getShipsState();

    function reset() : void;

}