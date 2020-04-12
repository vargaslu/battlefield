<?php


namespace Game\Battleship;

use InvalidArgumentException;

final class ShipFactory {

    private $shipName;

    public function __construct($shipName) {
        $this->shipName = strtoupper($shipName);
    }

    public function buildWithLocation(Location $location, $direction) : Ship {
        switch ($this->shipName) {
            case strtoupper(Carrier::NAME):
                return Carrier::build($location, $direction);
            case strtoupper(Battleship::NAME):
                return Battleship::build($location, $direction);
            case strtoupper(Destroyer::NAME):
                return Destroyer::build($location, $direction);
            case strtoupper(Submarine::NAME):
                return Submarine::build($location, $direction);
            case strtoupper(Cruiser::NAME):
                return Cruiser::build($location, $direction);
            default:
                throw new InvalidArgumentException('Ship name ' . $this->shipName . ' is not valid');
        }
    }

    public static function fromJson($json) : Ship {
        $shipFactory = new ShipFactory($json['name']);
        return $shipFactory->buildWithLocation(Location::fromJson($json), Direction::fromJson($json));
    }

}