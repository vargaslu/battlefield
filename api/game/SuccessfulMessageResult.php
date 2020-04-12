<?php


namespace Game\Battleship;

require_once __DIR__ . '/../../api/game/MessageResult.php';

class SuccessfulMessageResult implements MessageResult {

    private $message;

    public function __construct($message) {
        $this->message = $message;
    }

    public function jsonSerialize() {
        http_response_code(200);
        return [ 'message' => $this->message ];
    }
}