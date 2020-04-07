<?php


namespace Game\Battleship;

require_once 'Grid.php';
require_once __DIR__.'/../items/Peg.php';

class Target {

    private $grid;

    function __construct(Grid $grid) {
        $this->grid = $grid;
    }

    function place(Peg $peg) {
        $this->grid->put($peg, $peg->getLocation());
    }

    function peek(Location $location) {
        return $this->grid->getItem($location);
    }

    function getWhitePegs() {
        $filterClosure = $this->getClosureFilter(Peg::WHITE);
        return $this->grid->getFilteredGrid($filterClosure);
    }

    function getRedPegs() {
        $filterClosure = $this->getClosureFilter(Peg::RED);
        return $this->grid->getFilteredGrid($filterClosure);
    }

    private function getClosureFilter($pegType) {
        return function ($value) use ($pegType) {
            return $value === $pegType;
        };
    }
}