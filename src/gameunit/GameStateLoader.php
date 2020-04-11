<?php


namespace Game\Battleship;

require_once 'CallingShotsState.php';
require_once 'PlacingShipsState.php';
require_once 'WaitingForStartState.php';
require_once 'WaitingForAutomaticActionState.php';

class GameStateLoader {

    private static $gameStateLoader = NULL;

    private $waitingForStartState = NULL;

    private $placingShipsState = NULL;

    private $waitingForAutomaticActionState = NULL;

    private $callingShotsState = NULL;

    private function __construct() {
    }

    public static function loadWaitingForStartState() : WaitingForStartState {
        $gameLoader = self::loadGameStateLoader();
        if (NULL == $gameLoader->waitingForStartState) {
            $gameLoader->waitingForStartState = new WaitingForStartState();
        }
        return $gameLoader->waitingForStartState;
    }

    public static function loadPlacingShipsState() : PlacingShipsState {
        $gameLoader = self::loadGameStateLoader();
        if (NULL == $gameLoader->placingShipsState) {
            $gameLoader->placingShipsState = new PlacingShipsState();
        }
        return $gameLoader->placingShipsState;
    }

    public static function loadWaitingForAutomaticActionState() : WaitingForAutomaticActionState {
        $gameLoader = self::loadGameStateLoader();
        if (NULL == $gameLoader->waitingForAutomaticActionState) {
            $gameLoader->waitingForAutomaticActionState = new WaitingForAutomaticActionState();
        }
        return $gameLoader->waitingForAutomaticActionState;
    }

    public static function loadCallingShotsState() : CallingShotsState {
        $gameLoader = self::loadGameStateLoader();
        if (NULL == $gameLoader->callingShotsState) {
            $gameLoader->callingShotsState = new CallingShotsState();
        }
        return $gameLoader->callingShotsState;
    }

    private static function loadGameStateLoader() : GameStateLoader {
        if (NULL == self::$gameStateLoader) {
            self::$gameStateLoader = new GameStateLoader();
        }
        return self::$gameStateLoader;
    }
}