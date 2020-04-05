<?php


namespace Game\Battleship;

require_once __DIR__.'/../items/Battleship.php';
require_once __DIR__.'/../items/Carrier.php';
require_once __DIR__.'/../items/Cruiser.php';
require_once __DIR__.'/../items/Submarine.php';
require_once __DIR__.'/../items/Destroyer.php';
require_once __DIR__.'/../listeners/PropertyChangeListener.php';
require_once 'Ocean.php';
require_once 'Target.php';


class GameUnit implements PropertyChangeListener {

    private $ocean;

    private $target;

    private $placedShips;

    public function __construct() {
        $this->ocean = new Ocean(new Grid());
        $this->target = new Target(new Grid());
        $this->placedShips = [];
    }

    public function placeShip(Ship $ship, Location $location, $direction) {
        // TODO: Validations before placing.
        // 1. Verify if ship is available
        $this->ocean->place($ship, $location, $direction);
        $this->placedShips[$ship->getName()] = $ship;
        $ship->addPropertyChangeListener($this);
    }

    public function makeShot(Location $location) {
        // TODO: mark in target
        // TODO: Service to verifyShot in opponent
    }

    public function receiveShot(Location $location) {
        $peekResult = $this->ocean->peek($location);
        if (strcmp('', $peekResult) == 0) {
            return HitResult::createMissedHitResult();
        }

        $ship = $this->placedShips[$peekResult];
        $ship->hit();
        return HitResult::createSuccessfulHitResult($peekResult);
    }

    public function availableShips() {
        return sizeof($this->placedShips);
    }

    function fireUpdate($ship, $oldValue, $newValue) {
        if (strcmp($newValue, 'sank') == 0) {
            unset($this->placedShips[$ship]);
        }
    }
}