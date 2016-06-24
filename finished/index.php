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
	    <p style="font-style: italic">Total of <?php echo number_format(sizeof($finishedGames), 0, ",", " ")?> games played and <?php echo number_format($totalThrowCount, 0, ",", " ")?> darts thrown. Click on column to sort.</p>

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
	        	<?php
	        	foreach ($finishedGames as $game){
	        	?>
		            <tr>
		                <td class="name"><?php echo $game->getPlayer()->getName(); ?></td>
		                <td><span class="started" data-timestamp="<?php echo strtotime($game->getTimeStarted())?>" data-time="<?php echo $game->getTimeStarted(); ?>"></span></td>
		                <td><span class="finished" data-timestamp="<?php echo strtotime($game->getTimeFinished())?>" data-time="<?php echo $game->getTimeFinished(); ?>"></span></td>
		                <td class="text-center" style="font-weight:bold" id="game-<?php echo $game->getGameId()?>" data-player-id="<?php echo $game->getPlayer()->getPlayerId()?>"><?php echo $game->getNumThrows(); ?></td>
		                <td style="text-align: right; font-weight: normal;"><a href="game/?id=<?php echo $game->getGameId()?>">View game</a></td>
		            </tr>
		        <?php } ?>
	        </tbody>
	    </table>
	</section>
</div>
<?php include('../views/partials/footer.php'); ?>
<script src="../scripts/finished-games.js"></script>