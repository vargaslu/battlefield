<?php


namespace Game\Battleship;

require_once __DIR__.'/../../api/game/MessageResult.php';

class ExceptionMessageResult implements MessageResult {

    private $message;

    public function __construct($message) {
        $this->message = $message;
    }

    public function jsonSerialize() {
        http_response_code(400);
        return [ 'message' => $this->message ];
    }
}