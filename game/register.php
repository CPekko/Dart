<?php
date_default_timezone_set("Europe/Oslo");
require_once('../partials/classes.php');
require_once('../partials/funcs.php');
if (isset($_POST['data']) && isset($_POST['gameId'])){
	$data = json_decode($_POST['data']);
	$gameId = $_POST['gameId'];
	$dartThrows = array();
	foreach ($data as $dartThrow) {
		$dartThrows[] = new DartThrow($dartThrow->target, $dartThrow->hit, $dartThrow->streak);
	}
	registerThrows($gameId, $dartThrows);
}

?>