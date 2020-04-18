<?php


namespace Game\Battleship;

use function PHPUnit\throwException;

require_once __DIR__.'/../items/Battleship.php';
require_once __DIR__.'/../items/Carrier.php';
require_once __DIR__.'/../items/Cruiser.php';
require_once __DIR__.'/../items/Submarine.php';
require_once __DIR__.'/../items/Destroyer.php';
require_once __DIR__.'/../listeners/PropertyChangeListener.php';
require_once __DIR__.'/../listeners/ShipDestroyedListener.php';
require_once __DIR__.'/../exceptions/NotAllowedShipException.php';
require_once 'Ocean.php';
require_once 'Target.php';


class GameUnit {

    private $ocean;

    private $target;

    private $placedShips;

    private $originalPlacedShips;

    private $gameService;

    private $shipDestroyedListener;

    private $readyListener;

    private $owner;

    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
        $this->ocean = new Ocean(new Grid());
        $this->target = new Target(new Grid());
        $this->placedShips = [];
        $this->originalPlacedShips = [];
        $this->shipDestroyedListener = new ShipDestroyedListener($this->placedShips);
        $this->setOwner('Player 1');
    }

    public function setOwner(string $owner): void {
        $this->owner = $owner;
        $this->shipDestroyedListener->setOwner($owner);
    }

    public function getOwner(): string {
        return $this->owner;
    }

    public function setReadyListener(PropertyChangeListener $readyListener) {
        $this->readyListener = $readyListener;
    }

    public function setEndGameListener(PropertyChangeListener $endGameListener) {
        $this->shipDestroyedListener->setEndGameListener($endGameListener);
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
        $ship->addPropertyChangeListener($this->shipDestroyedListener);

        $this->originalPlacedShips = $this->placedShips;

        $result = array_diff(Constants::$DEFAULT_SHIPS_TO_PLACE, array_keys($this->placedShips));
        if (sizeof($result) === 0) {
            error_log('>>Ready!!! '. $this->getOwner());
            $this->readyListener->fireUpdate(Constants::POSITIONED_SHIPS, ReadyListener::READY, true);
        }
    }

    public function makeShot(Location $location) : HitResult {
        $this->target->validateLocation($location);
        $hitResult = $this->gameService->makeShot($this, $location);
        if ($hitResult->isHit()) {
            $this->target->place(Peg::createRedPeg($location));
        } else {
            $this->target->place(Peg::createWhitePeg($location));
        }

        $this->readyListener->fireUpdate(Constants::CALLED_SHOT, ReadyListener::READY, true);

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

    function getFreeAvailableTargetPositions() {
        return $this->target->getNotUsedGridPositions();
    }
}