<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../src/gameunit/GameController.php';
include_once '../../src/gameunit/GameControllerImpl.php';

use Game\Battleship\GameController;
use Game\Battleship\GameControllerImpl;

$action = "";
if(isset($_GET["action"])){
    $action = $_GET["action"];
}

$data = json_decode(file_get_contents("php://input"));

switch($action) {

    case "status_info":
        http_response_code(200);
        echo json_encode((new GameControllerImpl())->getCurrentState());
        break;
    case "start":
        http_response_code(200);
        echo json_encode( array("message" => "Name." . $data->name));
        break;
    default:
        http_response_code(404);
        echo json_encode( array("message" => "Request not found."));
}