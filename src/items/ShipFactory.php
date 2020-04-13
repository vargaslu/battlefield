<?php


namespace Game\Battleship;

use InvalidArgumentException;

final class ShipFactory {

    private $shipName;

    public function __construct($shipName) {
        $this->shipName = strtoupper($shipName);
    }

    public function buildWithLocation(ShipLocation $location) : Ship {
        switch ($this->shipName) {
            case strtoupper(Carrier::NAME):
                return Carrier::build($location);
            case strtoupper(Battleship::NAME):
                return Battleship::build($location);
            case strtoupper(Destroyer::NAME):
                return Destroyer::build($location);
            case strtoupper(Submarine::NAME):
                return Submarine::build($location);
            case strtoupper(Cruiser::NAME):
                return Cruiser::build($location);
            default:
                throw new InvalidArgumentException('Ship name ' . $this->shipName . ' is not valid');
        }
    }

    public function buildWithoutLocation() : Ship {
        switch ($this->shipName) {
            case strtoupper(Carrier::NAME):
                return Carrier::buildWithoutLocation();
            case strtoupper(Battleship::NAME):
                return Battleship::buildWithoutLocation();
            case strtoupper(Destroyer::NAME):
                return Destroyer::buildWithoutLocation();
            case strtoupper(Submarine::NAME):
                return Submarine::buildWithoutLocation();
            case strtoupper(Cruiser::NAME):
                return Cruiser::buildWithoutLocation();
            default:
                throw new InvalidArgumentException('Ship name ' . $this->shipName . ' is not valid');
        }
    }

    public static function fromJson($json) : Ship {
        $shipFactory = new ShipFactory($json['name']);
        return $shipFactory->buildWithLocation(ShipLocation::fromJson($json));
    }

}