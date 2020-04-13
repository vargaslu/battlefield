<?php


namespace Game\Battleship;

require_once 'Item.php';

use JsonSerializable;

abstract class Ship implements Item, JsonSerializable {

    public const DESTROYED = 'destroyed';

    private $name;

    private $size;

    private $lives;

    private $listeners;

    private $location;

    private $direction;

    protected function __construct($name, $size, Location $location, $direction) {
        $this->name = $name;
        $this->size = $size;
        $this->lives = $size;
        $this->location = $location;
        $this->direction = $direction;
        $this->listeners = [];
    }

    final function getName() {
        return $this->name;
    }

    final function getSize() {
        return $this->size;
    }

    final function hit() {
        $this->lives--;
        if (!$this->isAlive()) {
            $this->notifyShipIsSankToListeners();
        }
    }

    final function isAlive() {
        return $this->lives > 0;
    }

    function __toString() {
        return $this->name;
    }

    final function addPropertyChangeListener(PropertyChangeListener $listener) {
        $this->listeners[] = $listener;
    }

    final function getLocation(): Location {
        return $this->location;
    }

    final function getDirection() {
        return $this->direction;
    }

    private function notifyShipIsSankToListeners() {
        foreach ($this->listeners as $i => $value) {
            $listener = $this->listeners[$i];
            $listener->fireUpdate($this->getName(), '', Ship::DESTROYED);
        }
    }

    public function jsonSerialize() {
        $status = $this->isAlive() ? 'live' : 'destroyed';
        $hitsReceived = $this->getSize() - $this->lives;
        return [ 'name' => $this->getName(), 'status' => $status, 'hits_received' => $hitsReceived ];
    }
}