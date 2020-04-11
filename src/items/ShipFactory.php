<?php


namespace Game\Battleship;

use InvalidArgumentException;

final class ShipFactory {

    private $shipName;

    public function __construct($shipName) {
        $this->shipName = $shipName;
    }

    public function buildWithLocation(Location $location, $direction) : Ship {
        switch ($this->shipName) {
            case Carrier::NAME:
                return Carrier::build($location, $direction);
            case Battleship::NAME:
                return Battleship::build($location, $direction);
            case Destroyer::NAME:
                return Destroyer::build($location, $direction);
            case Submarine::NAME:
                return Submarine::build($location, $direction);
            case Cruiser::NAME:
                return Cruiser::build($location, $direction);
            default:
                throw new InvalidArgumentException('Ship name ' . $this->shipName . ' is not valid');
        }
    }

    public static function fromJson($json) : Ship {
        $shipFactory = new ShipFactory($json['name']);
        return $shipFactory->buildWithLocation(Location::fromJson($json), $json['direction']);
    }

}