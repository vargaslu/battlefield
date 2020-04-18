<?php


namespace Game\Battleship;

require_once __DIR__.'/../positioning/Location.php';
require_once 'GameService.php';
require_once 'HitResult.php';

class GameServiceImpl implements GameService {

    private $firstGameUnit;

    private $secondGameUnit;

    public function __construct() {
    }

    public function setFirstGameUnit(GameUnit $firstGameUnit): void {
        $this->firstGameUnit = $firstGameUnit;
    }

    public function setSecondGameUnit(GameUnit $secondGameUnit): void {
        $this->secondGameUnit = $secondGameUnit;
    }

    function makeShotFromSourceToOpponentLocation(GameUnit $source, Location $location) : HitResult {
        $gameUnitToReceiveShot = $this->chooseGameUnitToReceiveShot($source);
        return $gameUnitToReceiveShot->receiveShot($location);
    }

    private function chooseGameUnitToReceiveShot(GameUnit $source) : GameUnit {
        if ($source === $this->firstGameUnit) {
            return $this->secondGameUnit;
        }
        return $this->firstGameUnit;
    }
}