<?php


namespace Game\Battleship;


interface AttackStrategy {

    function makeShot() : void;
}