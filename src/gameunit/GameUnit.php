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

    private $gameService;

    private $shipDestroyedListener;

    private $readyListener;

    private $owner;

    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
        $this->ocean = new Ocean(new Grid());
        $this->target = new Target(new Grid());
        $this->placedShips = [];
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
        $this->validateShipIsNotPlacedMoreThanOnce($ship->getName());

        $this->ocean->place($ship);
        $this->placedShips[$ship->getName()] = $ship;
        $ship->addPropertyChangeListener($this->shipDestroyedListener);

        $this->notifyReadyListenerEndOfPlaceShips();
    }

    private function validateShipIsNotPlacedMoreThanOnce(string $shipName): void {
        if (array_search($shipName, $this->placedShips) !== false) {
            throw new NotAllowedShipException('Allowed quantity for ship ' . $shipName . ' already used');
        }
    }

    private function notifyReadyListenerEndOfPlaceShips(): void {
        $result = array_diff(Constants::$DEFAULT_SHIPS_TO_PLACE, array_keys($this->placedShips));

        if (sizeof($result) === 0) {
            $this->readyListener->fireUpdate(Constants::POSITIONED_SHIPS, ReadyListener::READY, true);
        }
    }

    public function makeShot(Location $location) : HitResult {
        $this->target->validateLocation($location);
        $hitResult = $this->gameService->makeShot($this, $location);
        $this->placePegInLocationAccordingToHitResult($hitResult, $location);

        $this->notifyReadyListenerEndOfCallingShots();

        return $hitResult;
    }

    private function placePegInLocationAccordingToHitResult(HitResult $hitResult, Location $location): void {
        if ($hitResult->isHit()) {
            $this->target->place(Peg::createRedPeg($location));
        } else {
            $this->target->place(Peg::createWhitePeg($location));
        }
    }

    private function notifyReadyListenerEndOfCallingShots(): void {
        $this->readyListener->fireUpdate(Constants::CALLED_SHOT, ReadyListener::READY, true);
    }

    public function receiveShot(Location $location) {
        $peekResult = $this->ocean->peek($location);
        if (strcmp('', $peekResult) == 0) {
            return HitResult::createMissedHitResult();
        }

        error_log('Receiving shot in: '. $location);
        $ship = $this->placedShips[$peekResult];
        $ship->hit();
        return HitResult::createSuccessfulHitResult($peekResult);
    }

    public function availableShips() {
        return array_reduce($this->placedShips, $this->countAliveShipsClosure());
    }

    private function countAliveShipsClosure() {
        return function ($carry, Ship $ship) {
            $carry += ($ship->isAlive()) ? 1 : 0;
            return $carry;
        };
    }

    public function getPlacedShips(): array {
        return $this->placedShips;
    }

    function getFreeAvailableTargetPositions() {
        return $this->target->getNotUsedGridPositions();
    }
}