<?php


namespace Game\Battleship;

use JsonSerializable;

interface GameState extends JsonSerializable {

    function placingShips(GameUnit $current, Ship $ship);

    function callingShot(Location $location);

    function enter($value = null);

}