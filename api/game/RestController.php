<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include 'Context.php';
include 'SuccessfulMessageResult.php';
include 'ExceptionMessageResult.php';

use Game\Battleship\Context;
use Game\Battleship\ExceptionMessageResult;
use Game\Battleship\SuccessfulMessageResult;

const GAME_CONTROLLER = 'game_controller';

$action = '';
if (isset($_GET['action'])){
    $action = $_GET['action'];
}

$data = json_decode(file_get_contents('php://input'), true);

$gameController = NULL;

if (isset($_SESSION[GAME_CONTROLLER])) {
    $gameController = unserialize($_SESSION[GAME_CONTROLLER]);
} else {
    $gameController = Context::loadGameController();
    $_SESSION[GAME_CONTROLLER] = serialize($gameController);
}

doAction($action, $gameController, $data);

function doAction($action, $gameController, $data) {
    switch($action) {
        case 'status_info':
            http_response_code(200);
            echo json_encode($gameController->getCurrentState());
            break;
        case 'start':
            echo json_encode(tryStartGame($data, $gameController));
            saveGameControllerInSession($gameController);
            break;
        case 'place_ships':
            echo json_encode(tryPlaceShips($data, $gameController));
            saveGameControllerInSession($gameController);
            break;
        case 'call_shot':
            echo json_encode(tryCallShot($data, $gameController));
            saveGameControllerInSession($gameController);
            break;
        case 'ships_status':
            echo json_encode($gameController->getShipsState());
            break;
        case 'reset':
            unset($_SESSION[GAME_CONTROLLER]);
            http_response_code(200);
            echo json_encode( array('message' => 'Successful game reset.'));
            break;
        default:
            http_response_code(404);
            echo json_encode( array('message' => 'Request not found.'));
    }
}

function saveGameControllerInSession($gameController) {
    $_SESSION[GAME_CONTROLLER] = serialize($gameController);
}

function tryStartGame($data, $gameController) {
    try {
        $gameController->start($data);
        return new SuccessfulMessageResult('Game started successfully');
    } catch (Exception $exception) {
        return new ExceptionMessageResult($exception->getMessage());
    }
}

function tryPlaceShips($data, $gameController) {
    try {
        $results = [];
        foreach ($data as $shipData) {
            $placeShipResult = $gameController->placeShip($shipData);
            array_push($results, $placeShipResult);
        }
        return $results;
    } catch (Exception $exception) {
        return new ExceptionMessageResult($exception->getMessage());
    }
}

function tryCallShot($data, $gameController) {
    try {
        return $gameController->callShot($data);
    } catch (Exception $exception) {
        return new ExceptionMessageResult($exception->getMessage());
    }
}