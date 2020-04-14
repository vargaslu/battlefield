<?php


namespace Game\Battleship;

require_once __DIR__.'/../items/ShipFactory.php';
require_once __DIR__.'/../listeners/ReadyListener.php';
require_once __DIR__.'/../positioning/ShipLocation.php';
require_once __DIR__.'/../states/GameState.php';

require_once 'Utils.php';
require_once 'RandomAttackStrategy.php';

use Exception;

class PlayerEmulator {

    private $shipsToPlace;

    private $gameUnit;

    private $listener;

    private $attackStrategy;

    public function __construct(GameUnit $gameUnit) {
        $this->gameUnit = $gameUnit;
        $this->shipsToPlace = Constants::$DEFAULT_SHIPS_TO_PLACE;
        $this->attackStrategy = new RandomAttackStrategy($this->gameUnit);
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
                $shipLocation = $this->getRandomShipLocation();
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

        $this->attackStrategy->makeShot();

        $this->listener->fireUpdate(Constants::CALLED_SHOT, ReadyListener::READY, true);
    }

    public function setAttackStrategy(AttackStrategy $attackStrategy) {
        $this->attackStrategy = $attackStrategy;
    }

    protected function getRandomLocation() : Location {
        return Utils::getRandomLocation();
    }

    protected function getRandomShipLocation() {
        return Utils::getRandomShipLocation();
    }

    protected function getGameUnit(): GameUnit {
        return $this->gameUnit;
    }
}