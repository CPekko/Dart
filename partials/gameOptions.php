<?php
require_once('partials/funcs.php');
if (isset($_GET['action'])){
	$action = $_GET['action'];

	//Abandon game
	if($action == 1 && isset($_GET['gameID']) && isset($_GET['playerID'])){
		$gameID = $_GET['gameID'];
		finishGame($gameID, 1);
		header("Location: http://folk.ntnu.no/eiriknf/dart/");
	    die();
	}

	//Start new game
	elseif($action == 2 && isset($_GET['playerID'])){
		$playerId = $_GET['playerID'];
		startNewGame($playerId);
		header("Location: http://folk.ntnu.no/eiriknf/dart/");
	    die();
	}
}
?>