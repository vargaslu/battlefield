<?php


namespace Game\Battleship;

require_once 'Ship.php';

final class Carrier extends Ship {

    function __construct() {
        parent::__construct("Carrier", 5);
    }

}