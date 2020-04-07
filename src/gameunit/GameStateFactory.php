<?php


namespace Game\Battleship;

require_once 'PlacingShipsState.php';
require_once 'AutomatedPlacingShipsState.php';

final class GameStateFactory {

    public static function makePlacingShipsStateFactory(GameController $gameController,
                                                        GameUnit $current,
                                                        GameUnit $opponent) : GameState {
        // In case of more Players they could be build here
        // But actually placing ships should/could be done in parallel
        return new AutomatedPlacingShipsState($gameController, $current, $opponent);
    }
}