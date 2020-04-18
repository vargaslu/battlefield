# Battleship
Small battleship game using PHP, an explanation of the basic rules can be found [here](https://www.hasbro.com/common/instruct/Battleship.PDF).
The game uses JSON REST API calls. 

##Start the game
There are two ways to start the game server locally:
### Using PHP Built-in Server
```
$php -S localhost:<port>
``` 
### Using Docker
Using Apache
```
$docker build -t battleship_game .
$docker run -d -p <port>:80 --name battleship battleship_game
```
_Note: <port> is the desired port to be used_

Verify that the game has started:
```
http://localhost:<port>/api/game/RestController.php?action=status_info
```
The response should be a JSON message containing the following state:
```
{
    "state": "Waiting for start"
}
```
##Authors
- *Luis Vargas*