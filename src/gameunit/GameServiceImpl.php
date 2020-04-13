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

    function makeShot(GameUnit $source, Location $location) : HitResult {
        $gameUnitToReceiveShot = $this->firstGameUnit;
        if ($source === $this->firstGameUnit) {
            $gameUnitToReceiveShot = $this->secondGameUnit;
        }
        return $gameUnitToReceiveShot->receiveShot($location);
    }
}