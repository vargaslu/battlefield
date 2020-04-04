<?php


namespace Game\Battleship;

require_once 'Grid.php';
require_once __DIR__.'/../items/Peg.php';

class Target {

    private $grid;

    function __construct(Grid $grid) {
        $this->grid = $grid;
    }

    function place(Peg $peg, Location $location) {
        $this->grid->put($peg, $location);
    }

}