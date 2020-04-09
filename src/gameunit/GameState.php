<?php


namespace Game\Battleship;


interface GameState {

    function placingShips(GameUnit $current, Ship $ship);

    function callingShot(Location $location);

}