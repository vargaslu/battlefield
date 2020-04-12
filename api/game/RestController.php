<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'Context.php';
include 'SuccessfulMessageResult.php';
include 'ExceptionMessageResult.php';

use Game\Battleship\Context;
use Game\Battleship\ExceptionMessageResult;
use Game\Battleship\SuccessfulMessageResult;

use Game\Battleship\GameStateLoader;

ini_set('xdebug.var_display_max_depth', '10');
ini_set('xdebug.var_display_max_children', '256');
ini_set('xdebug.var_display_max_data', '1024');

$action = "";
if (isset($_GET["action"])){
    $action = $_GET["action"];
}

$data = json_decode(file_get_contents("php://input"), true);

$gameController = NULL;

if (isset($_SESSION['game_controller'])) {
    $gameController = unserialize($_SESSION['game_controller']);
} else {
    $gameController = Context::loadGameController();
    $_SESSION['game_controller'] = serialize($gameController);
}

switch($action) {

    case "status_info":
        http_response_code(200);
        var_dump($gameController);
        echo json_encode($gameController->getCurrentState());
        break;
    case "start":
        echo json_encode(tryStartGame($gameController));
        $_SESSION['game_controller'] = serialize($gameController);
        break;
    case "place_ships":
        echo json_encode(tryPlaceShips($data, $gameController));
        $_SESSION['game_controller'] = serialize($gameController);
        break;
    case "call_shot":
        echo json_encode(tryCallShot($data, $gameController));
        $_SESSION['game_controller'] = serialize($gameController);
        break;
    case "reset":
        unset($_SESSION['game_controller']);
        break;
    default:
        http_response_code(404);
        echo json_encode( array("message" => "Request not found."));

}

function tryStartGame($gameController) {
    try {
        $gameController->start();
        return new SuccessfulMessageResult('Game started successfully');
    } catch (Exception $exception) {
        return new ExceptionMessageResult($exception->getMessage());
    }
}

function tryPlaceShips($data, $gameController) {
    try {
        foreach ($data as $shipData) {
            $gameController->placeShip($shipData);
        }
        return new SuccessfulMessageResult('Ship(s) placed successfully');
    } catch (Exception $exception) {
        return new ExceptionMessageResult($exception->getMessage());
    }
}

function tryCallShot($data, $gameController) {
    try {
        $gameController->callShot($data);
        return new SuccessfulMessageResult('Game started successfully');
        // TODO: Actually it returns HitResult
    } catch (Exception $exception) {
        return new ExceptionMessageResult($exception->getMessage());
    }
}