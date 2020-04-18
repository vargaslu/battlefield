<?php


namespace Game\Battleship;

require_once 'AttackStrategy.php';

class RandomAttackStrategy implements AttackStrategy {

    private $gameUnit;

    public function __construct(GameUnit $gameUnit) {
        $this->gameUnit = $gameUnit;
    }

    function makeShot(): void {
        $tries = 0;
        while (true) {
            try {
                $location = Utils::getRandomLocation();
                $this->gameUnit->callShotIntoLocation($location);
                break;
            } catch (Exception $exception) {
                error_log($exception->getMessage());
                if ($tries === $this->getMaxTries()) {
                    throw new Exception('Unable to make shots');
                }
                $tries++;
            }
        }
    }
}