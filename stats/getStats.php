<?php
include("../partials/funcs.php");

if(isset ($_GET['id']) && isset($_GET['type'])){
	$playerId = $_GET['id'];
	$type = $_GET['type'];

	if($type == '1'){
		echo $_GET['callback'] . "(" . json_encode(getAccuracyStats($playerId)) . ");";
	} elseif ($type == '2') {
		echo $_GET['callback'] . "(" . json_encode(getStreakLength($playerId)) . ");";
	} elseif ($type == '3') {
		echo $_GET['callback'] . "(" . json_encode(getHitRatio($playerId)) . ");";
	} elseif ($type == '4') {
		echo $_GET['callback'] . "(" . json_encode(getTotalHitCounts($playerId)) . ");";
	}
}

?>

