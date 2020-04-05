<?php


namespace Game\Battleship;


interface PropertyChangeListener {

    function fireUpdate($obj, $oldValue, $newValue);

}