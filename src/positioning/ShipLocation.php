<?php


namespace Game\Battleship;

require_once 'Location.php';

class ShipLocation extends Location {

    private $direction;

    public function __construct(string $letter, $column, $direction) {
        parent::__construct($letter, $column);
        $this->direction = Direction::validate($direction);
    }

    public static function fromJson($json) {
        return new ShipLocation($json['location'], $json['column'] , $json['direction']);
    }

    public function getDirection(): int {
        return $this->direction;
    }

    public function increase($size) : void {
        for ($i = 0; $i < $size; $i++) {
            $this->increaseLetterOrColumn();
        }
    }

    private function increaseLetterOrColumn(): void {
        if ($this->getDirection() == Direction::HORIZONTAL) {
            $this->increaseColumn();
        } else if ($this->getDirection() == Direction::VERTICAL) {
            $this->increaseLetter();
        }
    }

}