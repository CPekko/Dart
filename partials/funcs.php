<?php
//Connect to db
require_once('db-connect.php');

//Get list of throws per target
function getThrowList($dartGame){
	$hitList = array();
	foreach ($dartGame->getThrows() as $dartThrow) {
		$hitList[$dartThrow->getTarget()] ++;
	}
	return $hitList;
}

//Get all players
function getAllPlayers(){
	global $mysqli;
	$query = "SELECT PlayerID, Name, Active, Image FROM Players";
	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->bind_result($id, $name, $active, $image);
	
	while ($stmt->fetch()){
		$player = new Player($id, $name, $active);
		if (! is_null($image)){
			$player->setImage($image);
		}
		$players[] = $player;
	}
	$stmt->close();
	return $players;
}

//Get player by id
function getPlayer($playerId){
	global $mysqli;
	$query = "SELECT PlayerID, Name, Active, Image FROM Players WHERE PlayerID = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $playerId);
	$stmt->execute();
	$stmt->bind_result($id, $name, $active, $image);
	
	while ($stmt->fetch()){
		$player = new Player($id, $name, $active);
		if (! is_null($image)){
			$player->setImage($image);
		}
	}
	$stmt->close();
	return $player;
}

//Create new player
function createPlayer($player){
	global $mysqli;
	$name = $player->getName();
	$active = ($player->isActive() ?  1 :  0);
	$query = "INSERT INTO Players(Name, Active) VALUES (?,?)";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("si", $name, $active);
	$stmt->execute();
	$stmt->close();
	return;
}

//Edit player
function updatePlayer($player){
	global $mysqli;
	$name = $player->getName();
	$id = $player->getPlayerId();
	$image = $player->getImage();
	$active = ($player->isActive() ?  1 :  0);
	$query = "UPDATE Players SET Name = ?, Active = ?, Image = ? WHERE PlayerID = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sisi", $name, $active, $image, $id);
	$stmt->execute();
	$stmt->close();
	return;
}

//Get active players
function getActivePlayers(){
	global $mysqli;
	$active = 1;
	$query = "SELECT PlayerID, Name, Image FROM Players WHERE Active = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $active);
	$stmt->execute();
	$stmt->bind_result($id, $name, $image);
	
	while ($stmt->fetch()){
		$player = new Player($id, $name, $active);
		if (! is_null($image)){
			$player->setImage($image);
		}
		$players[] = $player;
	}
	$stmt->close();
	return $players;
}

//Get ongoing came for one player
function getActiveGame($player){
	global $mysqli;
	$unfinished = 0;
	$query = "SELECT GameID, Started FROM Games WHERE PlayerID = ? AND Finished = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ii", $player->getPlayerId(), $unfinished);
	$stmt->execute();
	$stmt->bind_result($gameID, $started);
	while ($stmt->fetch()){
		$game = new DartGame($gameID, $player, null, $started, null);
	}
	$stmt->close();
	if (isset($game)){
		$gameThrows = getGameThrows($game->getGameId());
		$game->setThrows($gameThrows);
		$game->setNumThrows(sizeof($gameThrows));
	}
	return $game;
}

//Get game by id
function getGame($gameId){
	global $mysqli;
	$query = "SELECT Name, PlayerID, Image, Started, Finished FROM Games NATURAL JOIN Players WHERE GameID = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $gameId);
	$stmt->execute();
	$stmt->bind_result($name, $playerId, $image, $started, $finished);
	while ($stmt->fetch()){
		$player = new Player($playerId, $name, 1);
		if (! is_null($image)){
			$player->setImage($image);
		}
		$game = new DartGame($gameId, $player, null, $started, $finished);
	}
	$stmt->close();
	if (isset($game)){
		$gameThrows = getGameThrows($game->getGameId());
		$game->setThrows($gameThrows);
		$game->setNumThrows(sizeof($gameThrows));
	}
	return $game;
}

function getFinishedGames(){
	global $mysqli;
	$finished = 0;
	$abandoned = 0;
	$query = "SELECT PlayerID, Name, Active, GameID, Started, Finished, count(GameID) AS NumThrows FROM Throws NATURAL JOIN Games NATURAL JOIN Players WHERE NOT Finished = ? AND Abandoned = ? GROUP BY GameID ORDER BY Finished DESC";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ii", $finished, $abandoned);
	$stmt->execute();
	$stmt->bind_result($playerId, $name, $active, $gameId, $started, $finished, $numThrows);
	while ($stmt->fetch()){
		$player = new Player($playerId, $name, $active);
		$game = new DartGame($gameId, $player, null, $started, $finished);
		$game->setNumThrows($numThrows);
		$games[] = $game;
	}
	$stmt->close();
	return $games;
}

//Get all throws for specific game
function getGameThrows($gameId){
	global $mysqli;
	$query = "SELECT Target, Hit, Streak FROM Throws WHERE GameID = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $gameId);
	$stmt->execute();
	$stmt->bind_result($target, $hit, $streak);
	$throws = array();
	while ($stmt->fetch()){
		$throws[] = new DartThrow($target, $hit, $streak);
	}
	$stmt->close();
	return $throws;
}

//Start new game
function startNewGame($playerId){
	global $mysqli;
	$query = "INSERT INTO Games(PlayerID) VALUES (?)";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $playerId);
	$stmt->execute();
	//$gameId = $stmt->insert_id;
	$stmt->close();
}

//Finish or abandon game.
function finishGame($gameId, $abandoned){
	global $mysqli;
	$now = date("Y-m-d H:i:s");
	$query = "UPDATE Games SET Finished = ?, Abandoned = ? WHERE GameID = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sii", $now, $abandoned, $gameId);
	$stmt->execute();
	$stmt->close();	
}

//Register array of throws in DB.
function registerThrows($gameId, $throws){
	global $mysqli;
	$finishedGame = false;
	$query = "INSERT INTO Throws(GameID, DistanceFromTarget, Target, Hit, Streak) VALUES (?,?,?,?,?)";
	$stmt = $mysqli->prepare($query);
	
	foreach ($throws as $dartThrow) {
		$dft = $dartThrow->getDistanceFromTarget();
		$target = $dartThrow->getTarget();
		$hit = $dartThrow->getHit();
		$streak = $dartThrow->getStreak();
		if($target == 20 && $hit==20) $finishedGame = true;
		$stmt ->bind_param("iiiii", $gameId, $dft, $target, $hit, $streak);
	    $stmt->execute();
	}
	$stmt->close();

	if ($finishedGame) finishGame($gameId, 0);
}

//Add new player
function addNewPlayer($name, $active){
	global $mysqli;
	$query = "INSERT INTO Player(Name, Active) VALUES (?,?)";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("si", $name, $active);
	$stmt->execute();
	$gameId = $stmt->insert_id;
	$stmt->close();
}

//Get number of finsihed games
function getNumberOfGames($player){
	global $mysqli;
	$unfinished = 0;
	$notAbandoned = 0;
	$query = "SELECT count(*) FROM Games WHERE NOT Finished = ? AND Abandoned = ? AND PlayerID = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("iii", $unfinished, $notAbandoned, $player->getPlayerId());
	$stmt->execute();
	$stmt->bind_result($count);
	while ($stmt->fetch()){
		$finishedGameCount = $count;
	}
	$stmt->close();
	return $finishedGameCount;
}

//Get number of arrows thrown
function getArrowsThrown($player){
	global $mysqli;
	$query = "SELECT count(*) FROM Throws NATURAL JOIN Games WHERE PlayerID = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $player->getPlayerId());
	$stmt->execute();
	$stmt->bind_result($count);
	while ($stmt->fetch()){
		$arrowsThrown = $count;
	}
	$stmt->close();
	return $arrowsThrown;
}

//Get number of target hits
function getTargetHits($player){
	global $mysqli;
	$distanceFromTarget = 0;
	$query = "SELECT count(*) FROM Throws NATURAL JOIN Games WHERE PlayerID = ? AND DistanceFromTarget = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ii", $player->getPlayerId(), $distanceFromTarget);
	$stmt->execute();
	$stmt->bind_result($count);
	while ($stmt->fetch()){
		$targetHits = $count;
	}
	$stmt->close();
	return $targetHits;
}

//Get longest streak
function getLongestStreak($player){
	global $mysqli;
	$query = "SELECT max(Streak) FROM Throws NATURAL JOIN Games WHERE PlayerID = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $player->getPlayerId());
	$stmt->execute();
	$stmt->bind_result($streak);
	while ($stmt->fetch()){
		$longestStreak = $streak;
	}
	$stmt->close();
	return $longestStreak;
}

function getBestGame($player){
	global $mysqli;
	$limit = 1;
	$abandoned = 0;
	$unfinished = 0;
	$query = "SELECT GameID, Started, Finished, count(Target) AS DartThrows FROM Throws NATURAL JOIN Games WHERE NOT Finished = ? AND Abandoned = ? AND PlayerID = ? GROUP BY GameID ORDER BY DartThrows ASC LIMIT ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("iiii", $unfinished, $abandoned, $player->getPlayerId(), $limit);
	$stmt->execute();
	$stmt->bind_result($gameID, $started, $finished, $throws);
	while ($stmt->fetch()){
		$game = new DartGame($gameID, $player, null, $started, $finished);
	}
	$stmt->close();
	if (isset($game)){
		$gameThrows = getGameThrows($game->getGameId());
		$game->setThrows($gameThrows);
		$game->setNumThrows(sizeof($gameThrows));
	}
	return $game;
}

function getWorstGame($player){
	global $mysqli;
	$limit = 1;
	$abandoned = 0;
	$unfinished = 0;
	$query = "SELECT GameID, Started, Finished, count(Target) AS DartThrows FROM Throws NATURAL JOIN Games WHERE NOT Finished = ? AND Abandoned = ? AND PlayerID = ? GROUP BY GameID ORDER BY DartThrows DESC LIMIT ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("iiii", $unfinished, $abandoned, $player->getPlayerId(), $limit);
	$stmt->execute();
	$stmt->bind_result($gameID, $started, $finished, $throws);
	while ($stmt->fetch()){
		$game = new DartGame($gameID, $player, null, $started, $finished);
	}
	$stmt->close();
	if (isset($game)){
		$gameThrows = getGameThrows($game->getGameId());
		$game->setThrows($gameThrows);
		$game->setNumThrows(sizeof($gameThrows));
	}
	return $game;
}

//Get average game
function getAverageGame($player){
	global $mysqli;
	$abandoned = 0;
	$unfinished = 0;
	$query = "SELECT Avg(DartThrows) FROM ( SELECT GameID, Started, Finished, count(Target) AS DartThrows FROM Throws NATURAL JOIN Games WHERE NOT Finished = ? AND Abandoned = ? AND PlayerID = ? GROUP BY GameID) AS Throws";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("iii", $unfinished, $abandoned, $player->getPlayerId());
	$stmt->execute();
	$stmt->bind_result($average);
	while ($stmt->fetch()){
		$averageGame = $average;
	}
	$stmt->close();
	return $averageGame;
}

//Get average for last 10 games.
function getAverageLast10($player){
	global $mysqli;
	$limit = 10;
	$abandoned = 0;
	$unfinished = 0;
	$query = "SELECT Avg(DartThrows) FROM ( SELECT GameID, Started, Finished, count(Target) AS DartThrows FROM Throws NATURAL JOIN Games WHERE NOT Finished = ? AND Abandoned = ? AND PlayerID = ? GROUP BY GameID ORDER BY Finished DESC LIMIT ?) AS Throws";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("iiii", $unfinished, $abandoned, $player->getPlayerId(), $limit);
	$stmt->execute();
	$stmt->bind_result($average);
	while ($stmt->fetch()){
		$average10 = $average;
	}
	$stmt->close();
	return $average10;
}

//Get accuracy stats
function getAccuracyStats($playerId){
	global $mysqli;
	$query = "SELECT DistanceFromTarget, count(*) FROM Throws NATURAL JOIN Games WHERE PlayerID = ? GROUP BY DistanceFromTarget ORDER BY DistanceFromTarget ASC";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $playerId);
	$stmt->execute();
	$stmt->bind_result($distanceFromTarget, $count);
	$distances = array();
	while ($stmt->fetch()){
		if (is_null($distanceFromTarget)) $distances[] = array('miss', $count);
		else $distances[] = array($distanceFromTarget, $count);
	}
	$stmt->close();
	return $distances;
}

//Get streak lengths stats
function getStreakLength($playerId){
	global $mysqli;
	$min1 = 0;
	$query = "SELECT Streak, count(*) FROM Throws NATURAL JOIN Games WHERE PlayerID = ? GROUP BY Streak HAVING Streak > ? ORDER BY Streak ASC";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ii", $playerId, $min1);
	$stmt->execute();
	$stmt->bind_result($streak, $count);
	$streaks = array();
	while ($stmt->fetch()){
		$streaks[] = array($streak, $count);
	}
	$stmt->close();
	return $streaks;
}

//Get hit ratio stats
function getHitRatio($playerId){
	global $mysqli;
	$distanceFromTarget = 0;
	//Get hits on target
	$query = "SELECT Target, count(*) FROM Throws NATURAL JOIN Games WHERE PlayerId = ? AND DistanceFromTarget = ? GROUP BY Target";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ii", $playerId, $distanceFromTarget);
	$stmt->execute();
	$stmt->bind_result($target, $count);
	$hits = array();
	while ($stmt->fetch()){
		$hits[$target] = $count;
	}
	$stmt->close();
	//Get all throws on target
	$query = "SELECT Target, count(*) FROM Throws NATURAL JOIN Games WHERE PlayerId = ? GROUP BY Target";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $playerId);
	$stmt->execute();
	$stmt->bind_result($target, $count);
	$targets = array();
	while ($stmt->fetch()){
		$targets[$target] = $count;
	}
	$stmt->close();
	$results = array();
	foreach ($targets as $target => $count){
		$results[] = array($target, round($hits[$target]/$count*100,2));
	}
	return $results;
}

//Get total hit count on a target.
function getTotalHitCounts($playerId){
	global $mysqli;
	$query = "SELECT Hit, count(*) FROM Throws NATURAL JOIN Games Where PlayerID = ? GROUP BY Hit ORDER BY Hit ASC";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("i", $playerId);
	$stmt->execute();
	$stmt->bind_result($hit, $count);
	$hitCounts = array();
	while ($stmt->fetch()){
		$hitCounts[] = $count;
	}
	$stmt->close();
	return $hitCounts;
}

function getTotalThrowCount(){
	global $mysqli;
	$query = "SELECT count(*) From Throws";
	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->bind_result($count);
	while ($stmt->fetch()){
		$count = $count;
	}
	$stmt->close();
	return $count;
}

?>