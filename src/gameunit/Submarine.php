<?php


namespace battleship\gameunit;

require_once 'Ship.php';

final class Submarine extends Ship {

    function __construct() {
        parent::__construct("Submarine", 3);
    }

}