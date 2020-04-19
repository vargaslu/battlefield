<?php


namespace Game\Battleship;

use Exception;

class GameStateException extends Exception {

    public function __construct($message = '', $currentGameState = '') {
        if (strcmp($currentGameState, '') != 0) {
            $message .=' - current state is: '.$currentGameState;
        }
        parent::__construct($message);
    }
}