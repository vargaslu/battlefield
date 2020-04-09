<?php


namespace Game\Battleship;

require_once __DIR__.'/../items/ShipFactory.php';
require_once 'GameState.php';
require_once 'GameUtils.php';

class PlayerEmulator {

    private $gameUtils;

    private $shipsToPlace;

    private $gameUnit;

    public function __construct(GameController $gameController, GameUnit $gameUnit) {
        $this->gameUnit = $gameUnit;
        $this->gameUtils = new GameUtils();
        $this->shipsToPlace = GameConstants::DEFAULT_SHIPS_TO_PLACE;
    }

    function setShipsToPlace($shipsToPlace) {
        $this->shipsToPlace = $shipsToPlace;
    }

    private function getShipsToPlace() {
        return $this->shipsToPlace;
    }

    function placeShips() {
        foreach ($this->getShipsToPlace() as $shipName) {
            $shipFactory = new ShipFactory($shipName);
            $location = $this->gameUtils->getRandomLocation();
            $direction = $this->gameUtils->getRandomDirection();
            $ship = $shipFactory->buildWithLocation($location, $direction);
            $this->gameUnit->placeShip($ship);
            // place into gameunit - if exception try again
            // save failed locations??
        }
        // TODO: At the end randomize CallingShot with random user start
    }
}