<?php


namespace Game\Battleship;

use JsonSerializable;

final class HitResult implements JsonSerializable {

    private $shipName;

    private $isHit;

    private function __construct($shipName, $isHit) {
        $this->shipName = $shipName;
        $this->isHit = $isHit;
    }

    public static function createMissedHitResult() {
        return new HitResult("", false);
    }

    public static function createSuccessfulHitResult($shipName) {
        return new HitResult($shipName, true);
    }

    public function getShipName() {
        return $this->shipName;
    }

    public function isHit() : bool {
        return $this->isHit;
    }

    public function jsonSerialize() {
        return [ 'is_hit' => $this->isHit(), 'ship_name' => $this->getShipName() ];
    }
}