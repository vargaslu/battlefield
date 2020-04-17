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

    private $originalPlacedShips;

    private $gameService;

    private $endListener;

    private $owner;

    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
        $this->ocean = new Ocean(new Grid());
        $this->target = new Target(new Grid());
        $this->placedShips = [];
        $this->originalPlacedShips = [];
        $this->owner = 'Player 1';
    }

    public function setOwner(string $owner): void {
        $this->owner = $owner;
    }

    public function getOwner(): string {
        return $this->owner;
    }

    public function setEndListener(PropertyChangeListener $endListener) {
        $this->endListener = $endListener;
    }

    public function isLocationFree(Location $location) : bool {
        $peekResult = $this->ocean->peek($location);
        return strcmp('', $peekResult) == 0;
    }

    public function isTargetLocationMarked(Location $location) : bool {
        $peekResult = $this->target->peek($location);
        return strcmp('', $peekResult) != 0;
    }

    public function placeShip(Ship $ship) {
        if (($key = array_search($ship->getName(), $this->placedShips)) !== false) {
            throw new NotAllowedShipException('Allowed quantity for ship ' . $ship->getName() . ' already used');
        }

        $this->ocean->place($ship);
        $this->placedShips[$ship->getName()] = $ship;
        $ship->addPropertyChangeListener($this);

        $this->originalPlacedShips = $this->placedShips;
    }

    public function makeShot(Location $location) : HitResult{
        $hitResult = $this->gameService->makeShot($this, $location);
        if ($hitResult->isHit()) {
            $this->target->place(Peg::createRedPeg($location));
        } else {
            $this->target->place(Peg::createWhitePeg($location));
        }
        return $hitResult;
    }

    public function receiveShot(Location $location) {
        $peekResult = $this->ocean->peek($location);
        if (strcmp('', $peekResult) == 0) {
            return HitResult::createMissedHitResult();
        }

        error_log('receiving shot in: '. $location);
        $ship = $this->placedShips[$peekResult];
        $ship->hit();
        return HitResult::createSuccessfulHitResult($peekResult);
    }

    public function availableShips() {
        return sizeof($this->placedShips);
    }

    public function getPlacedShips(): array {
        return $this->originalPlacedShips;
    }

    function fireUpdate($ship, $property, $value) {
        if (strcmp($value, Ship::DESTROYED) == 0) {
            unset($this->placedShips[$ship]);
            $this->notifyToListenersIfNoMoreShipsAreAvailable();
        }
    }

    function getFreeAvailableTargetPositions() {
        return $this->target->getNotUsedGridPositions();
    }

    private function notifyToListenersIfNoMoreShipsAreAvailable(): void {
        if (sizeof($this->placedShips) === 0) {
            error_log('notify Game Over');
            $this->endListener->fireUpdate($this, 'GAME_OVER', $this->owner);
        }
    }
}