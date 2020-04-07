<?php


namespace Game\Battleship;

use function PHPUnit\throwException;

require_once __DIR__.'/../items/Battleship.php';
require_once __DIR__.'/../items/Carrier.php';
require_once __DIR__.'/../items/Cruiser.php';
require_once __DIR__.'/../items/Submarine.php';
require_once __DIR__.'/../items/Destroyer.php';
require_once __DIR__.'/../listeners/PropertyChangeListener.php';
require_once __DIR__.'/../exceptions/NotAllowedShipException.php';
require_once 'Ocean.php';
require_once 'Target.php';


class GameUnit implements PropertyChangeListener {

    private $ocean;

    private $target;

    private $placedShips;

    private $gameService;

    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
        $this->ocean = new Ocean(new Grid());
        $this->target = new Target(new Grid());
        $this->placedShips = [];
    }

    public function placeShip(Ship $ship) {
        if (($key = array_search($ship->getName(), $this->placedShips)) !== false) {
            throw new NotAllowedShipException('Allowed quantity for ship ' . $ship->getName() . ' already used');
        }

        $this->ocean->place($ship);
        $this->placedShips[$ship->getName()] = $ship;
        $ship->addPropertyChangeListener($this);
    }

    public function makeShot(Location $location) {
        $hitResult = $this->gameService->makeShot($this, $location);
        if ($hitResult->isHit()) {
            $this->target->place(Peg::createRedPeg($location));
        } else {
            $this->target->place(Peg::createWhitePeg($location));
        }
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
        if (strcmp($newValue, Ship::DESTROYED) == 0) {
            unset($this->placedShips[$ship]);
            // TODO: if size of placedships is 0 notify that game is over
        }
    }
}