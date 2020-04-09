<?php


namespace Game\Battleship;


interface PropertyChangeListener {

    function fireUpdate($obj, $property, $value);

}