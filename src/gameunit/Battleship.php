<?php


namespace Battleship\GameUnit;

require_once 'Ship.php';

final class Battleship extends Ship {

    function __construct() {
        parent::__construct("Battleship", 4);
    }

}