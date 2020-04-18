<?php


namespace Game\Battleship;


final class Constants {

    public const MAX_PLAYERS = 2;

    public const POSITIONED_SHIPS = 'positioned_ships';

    public const CALLED_SHOT = 'called_shot';

    public static $DEFAULT_SHIPS_TO_PLACE = [Carrier::NAME,
                                             Destroyer::NAME,
                                             Cruiser::NAME,
                                             Submarine::NAME,
                                             Battleship::NAME];

    public static $CONFIGURED_HUMAN_PLAYERS = 1;

    private function __construct() {
    }

}