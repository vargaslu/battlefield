<?php


namespace Game\Battleship;

require_once __DIR__.'/../items/ShipFactory.php';
require_once __DIR__.'/../listeners/ReadyListener.php';
require_once __DIR__.'/../positioning/ShipLocation.php';
require_once __DIR__.'/../states/GameState.php';

require_once 'Utils.php';

use Exception;

class PlayerEmulator {

    private $gameUtils;

    private $shipsToPlace;

    private $gameUnit;

    private $listener;

    private $successfulHitLocation;

    private $successfulHitShip;

    public function __construct(GameUnit $gameUnit) {
        $this->gameUnit = $gameUnit;
        $this->gameUtils = new Utils();
        $this->shipsToPlace = Constants::$DEFAULT_SHIPS_TO_PLACE;
    }

    final function addPropertyChangeListener(PropertyChangeListener $listener) {
        $this->listener = $listener;
        return $this;
    }

    function placeShips() {
        foreach ($this->shipsToPlace as $shipName) {
            $this->searchForShipLocation($shipName);
        }

        $this->listener->fireUpdate(Constants::POSITIONED_SHIPS, ReadyListener::READY, true);
    }

    private function searchForShipLocation($shipName) {
        $tries = 0;
        while (true) {
            try {
                $shipFactory = new ShipFactory($shipName);
                $location = $this->getRandomLocation();
                $direction = $this->getRandomDirection();
                $shipLocation = new ShipLocation($location->getLetter(), $location->getColumn(), $direction);
                $ship = $shipFactory->buildWithLocation($shipLocation);
                $this->gameUnit->placeShip($ship);
                break;
            } catch (Exception $exception) {
                error_log($exception->getMessage());
                if ($tries === $this->getMaxTries()) {
                    throw new Exception('Unable to place ship ' . $shipName);
                }
                $tries++;
            }
        };
    }

    protected function getMaxTries() {
        return 10;
    }

    function makeShot() {
        $tries = 0;
        while (true) {
            try {
                $location = $this->calculateNextPossibleLocation();
                $hitResult = $this->gameUnit->makeShot($location);
                if ($hitResult->isHit()) {
                    $this->successfulHitLocation = $location;
                    $this->successfulHitShip = (new ShipFactory($hitResult->getShipName()))->buildWithoutLocation();
                }
                break;
            } catch (Exception $exception) {
                error_log($exception->getMessage());
                if ($tries === $this->getMaxTries()) {
                    throw new Exception('Unable to make shots');
                }
                $tries++;
            }
        }

        $this->listener->fireUpdate(Constants::CALLED_SHOT, ReadyListener::READY, true);
    }

    private function calculateNextPossibleLocation() : Location {
        if (isset($this->successfulHitLocation)) {
            return $this->getRandomLocation();
        } else {
            return $this->getRandomLocation();
        }
    }

    protected function getRandomLocation() : Location {
        return Utils::getRandomLocation();
    }

    protected function getRandomDirection() {
        return Utils::getRandomDirection();
    }
}