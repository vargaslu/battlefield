<?php


namespace Game\Battleship;


final class Context {

    private static $context = NULL;

    private $gameController = NULL;

    private function __construct() {
    }

    public static function loadGameController() : GameController {
        $loadContext = self::loadContext();
        if (NULL == $loadContext->gameController) {
            $loadContext->gameController = new GameControllerImpl();
        }
        return $loadContext->gameController;
    }

    private static function loadContext() : Context{
        if (NULL == self::$context) {
            self::$context = new Context();
        }
        return self::$context;
    }
}