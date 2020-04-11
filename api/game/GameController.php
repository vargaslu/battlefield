<?php


namespace Game\Battleship;


interface GameController {

    function start() : MessageResult;

    function placeShip($ship);

    function callShot($location);

    function getCurrentState() : GameState;

}