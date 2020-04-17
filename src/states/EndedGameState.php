<?php


namespace Game\Battleship;


class EndedGameState implements GameState {

    private $ownerFirstGameUnit;

    private $ownerSecondGameUnit;

    private $winnerPlayer;

    function registerFirstGameUnitOwner(string $owner) {
        $this->ownerFirstGameUnit = $owner;
    }

    function registerSecondGameUnitOwner(string $owner) {
        $this->ownerSecondGameUnit = $owner;
    }

    function placingShips(GameUnit $current, Ship $ship) {
        throw new GameStateException($this->getMessage());
    }

    function callingShot(GameUnit $current, Location $location) {
        throw new GameStateException($this->getMessage());
    }

    function enter($defeatedPlayer = null) {
        if (strcmp($defeatedPlayer, $this->ownerFirstGameUnit) == 0) {
            $this->winnerPlayer = $this->ownerSecondGameUnit;
        } else {
            $this->winnerPlayer = $this->ownerFirstGameUnit;
        }
    }

    private function getMessage() : string {
        return 'Game ended, '. $this->winnerPlayer .' won';
    }

    public function jsonSerialize() {
        return [ 'status' => $this->getMessage() ];
    }
}