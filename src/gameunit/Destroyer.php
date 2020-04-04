<?php

namespace Battleship\GameUnit;

require_once 'Ship.php';

final class Destroyer extends Ship {

    function __construct() {
        parent::__construct("Destroyer", 2);
    }

}