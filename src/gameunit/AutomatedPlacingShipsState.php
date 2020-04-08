<?php


namespace Game\Battleship;

require_once 'GameState.php';

class AutomatedPlacingShipsState implements GameState {

    private $gameUtils;

    public function __construct(GameController $gameController, GameUnit $current, GameUnit $opponent) {
        $this->gameUtils = new GameUtils();
        foreach (GameConstants::DEFAULT_SHIPS_TO_PLACE as $shipName) {
            $shipFactory = new ShipFactory($shipName);
            $location = $this->gameUtils->getRandomLocation();
            $direction = $this->gameUtils->getRandomDirection();
            $ship = $shipFactory->buildWithLocation($location, $direction);
            $current->placeShip($ship);
            // place into gameunit - if exception try again
            // save failed locations??
        }
        // TODO: At the end randomize CallingShot with random user start
    }

    function placingShips(Ship $ship) {
        // TODO: Message! User should not do nothing, Computer is placing ships
    }

    function callingShot(Location $location) {
        // TODO: Message! User should not do nothing, Computer is placing ships
    }
}