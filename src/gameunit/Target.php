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
        try {
            $this->grid->put($peg, $peg->getLocation());
        } catch (LocationException $exception) {
            throw new LocationException("Already used position");
        } catch (LocationOutOfBoundsException $exception) {
            throw new LocationOutOfBoundsException($peg->getLocation());
        }
    }

    function peek(Location $location) {
        return $this->grid->getItem($location);
    }

    function getNotUsedGridPositions() {
        $filterClosure = $this->getClosureFilter('');
        return $this->grid->getFilteredGridAsArray($filterClosure);
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