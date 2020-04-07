<?php


namespace Game\Battleship;


interface GameState {

    function placingShips(Ship $ship);

    function callingShot(Location $location);

}