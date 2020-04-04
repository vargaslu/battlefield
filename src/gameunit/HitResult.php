<?php


namespace Game\Battleship;


final class HitResult {

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

    public function isHit() {
        return $this->isHit;
    }
}