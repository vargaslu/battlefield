<?php


namespace Game\Battleship;

use Exception;

class LocationOutOfBoundsException extends Exception {

    public function __construct($location, $item = null) {
        $message = null;
        if (isset($item)) {
            $message = 'Item ' . $item . ' is located out of the board';
        } else {
            $message = 'Location '. $location . ' is out of the board';
        }
        parent::__construct($message);
    }
}