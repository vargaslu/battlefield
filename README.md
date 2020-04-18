# Battleship
Small battleship game using PHP, an explanation of the basic rules can be found [here](https://www.hasbro.com/common/instruct/Battleship.PDF).
The game uses JSON REST API calls. 

##Start the game server
There are two ways to start the game server locally:
### Using PHP Built-in Server
```
$php -S localhost:<port>
``` 
### Using Docker
A Dockerfile was created with an Apache Web Server.
```
$docker build -t battleship_game .
$docker run -d -p <port>:80 --name battleship battleship_game
```
_Note: <port> is the desired port to be used_

Verify in Postman that the game has started using:
```
http://localhost:<port>/api/game/RestController.php?action=status_info
```
The response should be a JSON message containing the following state:
```
{
    "state": "Waiting for start"
}
```
## Game instructions
In order to interact with the game the following actions are available:
### status_info
GET Method that gives information about the state of the game.
### start
POST Method to start the game.
### place_ships
POST Method to place the ships.
```json
[{
  "name" : "Carrier",
  "location": "A",
  "column": 1,
  "direction": "V"
}, {...}]
```
Possible ship names are \[ 'Carrier' | 'Cruiser' | 'Destroyer' | 'Battleship' | 'Submarine' \], location and 
column indicates where in the ocean grid will the ship be placed, direction can take two values
\[ 'V' | 'H' \] from vertical or horizontal.

### call_shot
POST Method to call shots.
```json
{
  "location" : "A",
  "column" : 6
}
```
Where location and column is the place where the shot will be called.
### ship_status
GET Method to see the status of the ships
### reset
GET Method to reset the game, and leaves it in waiting to start status.
##Authors
- *Luis Vargas*