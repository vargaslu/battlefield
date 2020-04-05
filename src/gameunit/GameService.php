<?php


namespace Game\Battleship;


interface GameService {

    function makeShot(GameUnit $source, Location $location);

}