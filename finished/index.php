<?php
date_default_timezone_set("Europe/Oslo");
require_once('../partials/classes.php');
require_once('../partials/funcs.php');
include('../views/partials/head.php');
$finishedGames = getFinishedGames();
$totalThrowCount = getTotalThrowCount();
?>
 <div class="container body-content">
	<section>
	    <style type="text/css">
	        .best-game td {
	            background-color: #eee;
	        }
	    </style>
		
	    <h2>Finished Games</h2>
	    <div class="records-wrapper"><label for="records">Show records only</label><input type="checkbox" name="records" class="record-box"></div>
	    <p style="font-style: italic">Total of <?echo number_format(sizeof($finishedGames), 0, ",", " ")?> games played and <?echo number_format($totalThrowCount, 0, ",", " ")?> darts thrown. Click on column to sort.</p>

	    <table class="table finished">
	        <thead>
	            <tr>
	                <th onclick="sortTable(1)">Player</th>
	                <th onclick="sortTable(2)">Started</th>
	                <th onclick="sortTable(3)">Finished</th>
	                <th onclick="sortTable(4)" class="text-center">Throws</th>
	                <th style="text-align: right">View game</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?
	        	foreach ($finishedGames as $game){
	        	?>
		            <tr>
		                <td class="name"><? echo $game->getPlayer()->getName(); ?></td>
		                <td><span class="started" data-timestamp="<?echo strtotime($game->getTimeStarted())?>" data-time="<? echo $game->getTimeStarted(); ?>"></span></td>
		                <td><span class="finished" data-timestamp="<?echo strtotime($game->getTimeFinished())?>" data-time="<? echo $game->getTimeFinished(); ?>"></span></td>
		                <td class="text-center" style="font-weight:bold" id="game-<?echo $game->getGameId()?>" data-player-id="<?echo $game->getPlayer()->getPlayerId()?>"><? echo $game->getNumThrows(); ?></td>
		                <td style="text-align: right; font-weight: normal;"><a href="game/?id=<?echo $game->getGameId()?>">View game</a></td>
		            </tr>
		        <?}?>
	        </tbody>
	    </table>
	</section>
</div>
<? include('../views/partials/footer.php'); ?>
<script src="../scripts/finished-games.js"></script>