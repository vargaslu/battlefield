<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'Context.php';

use Game\Battleship\Context;

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
        echo json_encode($gameController->getCurrentState());
        break;
    case "start":
        echo json_encode($gameController->start());
        $_SESSION['game_controller'] = serialize($gameController);
        break;
    case "place_ship":
        foreach ($data as $shipData) {
            echo json_encode($gameController->placeShip($shipData));
        }
        $_SESSION['game_controller'] = serialize($gameController);
        break;
    case "call_shot":
        echo json_encode($gameController->callShot($data));
        $_SESSION['game_controller'] = serialize($gameController);
        break;
    case "reset":
        unset($_SESSION['game_controller']);
        break;
    default:
        http_response_code(404);
        echo json_encode( array("message" => "Request not found."));

}