<?php


namespace Battleship\GameUnit;

require_once 'Ship.php';

final class Carrier extends Ship {

    function __construct() {
        parent::__construct("Carrier", 5);
    }

}