<?php


namespace Game\Battleship;


final class GameConstants {

    public static $DEFAULT_SHIPS_TO_PLACE = [Carrier::NAME, Destroyer::NAME, Submarine::NAME, Battleship::NAME];

    public static $MAX_PLAYERS = 2;

    public static $CONFIGURED_HUMAN_PLAYERS = 1;

    private function __construct() {
    }

}