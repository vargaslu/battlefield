<?php


namespace Game\Battleship;


interface GameRules {

    function makeShot(GameUnit $current ,Location $location);

}