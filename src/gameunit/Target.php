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
        $pegLocation = $peg->getLocation();
        $this->validateLocation($pegLocation);
        $this->grid->put($peg, $pegLocation);
    }

    function validateLocation(Location $location) {
        try {
            $this->grid->validateLocation($location);
        } catch (LocationException $exception) {
            throw new LocationException("Already used position");
        } catch (LocationOutOfBoundsException $exception) {
            throw new LocationOutOfBoundsException($location);
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

    function getAllUsedPegs() {
        $filterClosure = $this->getAllPegsClosureFilter();
        return $this->grid->getFilteredGridAsArray($filterClosure);
    }

    private function getClosureFilter($pegType) {
        return function ($value) use ($pegType) {
            return $value === $pegType;
        };
    }

    private function getAllPegsClosureFilter() {
        return function ($value) {
            return ($value !== '') || ($value === Peg::WHITE);
        };
    }
}