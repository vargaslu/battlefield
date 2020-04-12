<?php


namespace Game\Battleship;

use JsonSerializable;

interface GameState extends JsonSerializable {

    function placingShips(GameUnit $current, Ship $ship);

    function callingShot(GameUnit $current, Location $location);

    function enter($value = null);

}