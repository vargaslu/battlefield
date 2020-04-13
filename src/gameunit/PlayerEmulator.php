<?php


namespace Game\Battleship;

require_once __DIR__.'/../items/ShipFactory.php';
require_once __DIR__.'/../states/GameState.php';
require_once 'Utils.php';

use Exception;

class PlayerEmulator {

    private $gameUtils;

    private $shipsToPlace;

    private $gameUnit;

    private $listener;

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
        do {
            try {
                $shipFactory = new ShipFactory($shipName);
                $location = $this->gameUtils->getRandomLocation();
                $direction = $this->gameUtils->getRandomDirection();
                $ship = $shipFactory->buildWithLocation($location, $direction);
                $this->gameUnit->placeShip($ship);
                break;
            } catch (Exception $exception) {
                error_log($exception->getMessage());
            }
        } while (0);
    }

    function makeShot() {
        do {
            try {
                $location = $this->gameUtils->getRandomLocation();
                $this->gameUnit->makeShot($location);
                break;
            } catch (Exception $exception) {
                error_log($exception->getMessage());
            }
        } while (0);

        $this->listener->fireUpdate(Constants::CALLED_SHOT, ReadyListener::READY, true);
    }
}