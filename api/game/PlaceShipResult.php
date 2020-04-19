<?php


namespace Game\Battleship;

use JsonSerializable;

class PlaceShipResult implements JsonSerializable {

    private $shipName;

    private $status;

    private $message;

    private function __construct($shipName, $status, $message = '') {
        $this->shipName = $shipName;
        $this->status = $status;
        $this->message = $message;
    }

    public static function createSuccessful($shipName) : PlaceShipResult {
        return new PlaceShipResult($shipName, 'success');
    }

    public static function createFailed($shipName, $message) : PlaceShipResult {
        return new PlaceShipResult($shipName, 'failed', $message);
    }

    public function getShipName() : string {
        return $this->shipName;
    }

    public function getStatus() : string {
        return $this->status;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function jsonSerialize() {
        $returnMessage = ['ship_name' => $this->shipName, 'status' => $this->status];
        if (strcmp($this->message, '' ) != 0) {
            $returnMessage['message'] = $this->message;
        }
        return $returnMessage;
    }
}