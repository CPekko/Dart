<?php
date_default_timezone_set("Europe/Oslo");
require_once('../partials/classes.php');
require_once('../partials/funcs.php');
include('../views/partials/head.php');

if (isset($_GET['id'])){
	$playerId = $_GET['id'];
	$player = getPlayer($playerId);
	$game = getActiveGame($player);
    $dartboard = array(19,7,16,8,11,14,9,12,5,20,1,18,4,13,6,10,15,2,17,3);

	if ($player->getPlayerId() && $game->getGameId()){
		?>
<h2 class="play-header">
    <?echo $player->getName() ?>'s game<span>, started <span data-time="<?echo $game->getTimeStarted() ?>"></span></span>
</h2>
 <div class="container body-content">
	<section class="play">
    
    <div class="row">
        <div class="col-sm-8" style="height: 800px">
            <div>
            	<section>
				    <div class="board">
                        <a class="btn btn-danger" onclick="registerNewThrow(0)" style="position: absolute; right:0; top:7px; margin-right: 10px; padding: 20px; z-index:2;">Miss</a>
                        <a onclick="registerNewThrow(0)" style="position: absolute; left: 0; right:0; top:0; bottom:0;" > </a>
                        <div class="ring double">
				        <? foreach ($dartboard as $i => $j){ ?>
                            <a onclick="registerNewThrow(<?echo $j?>, 2)" class="piece piece-<?echo $i+1?>" ></a>
                        <?}?>
                        </div>
                        <div class="ring outer">
                        <? foreach ($dartboard as $i => $j){ ?>
                            <a onclick="registerNewThrow(<?echo $j?>,1)" class="piece piece-<?echo $i+1?>" ></a>
                        <?}?>
                        </div>
                        <div class="ring triple">
                        <? foreach ($dartboard as $i => $j){ ?>
                            <a onclick="registerNewThrow(<?echo $j?>,3)" class="piece piece-<?echo $i+1?>" ></a>
                        <?}?>
                        </div>
                        <div class="ring inner">
                        <? foreach ($dartboard as $i => $j){ ?>
                            <a onclick="registerNewThrow(<?echo $j?>,1)" class="piece piece-<?echo $i+1?>" ></a>
                        <?}?>
                        </div>
                        <div class="numbers">
                        <? foreach ($dartboard as $i => $j){ ?>
                             <a onclick="registerNewThrow(<?echo $j?>,1)" class="number number-<?echo $i+1?>" ><?echo $j?></a>
                        <?}?>
                        </div>
                        <a onclick="registerNewThrow(25,1)" class="bull ring" ></a>
                        <a onclick="registerNewThrow(50,1)" class="red-bull ring" ></a>
				    </div>
				</section>
            </div>
        </div>
        
        <div class="col-sm-2" style="margin-top: 10px">
            <div class="panel panel-default">
                <div class="panel-heading">Current Round</div>
                <ul class="list-group" id="listOfThrows">
                    
                </ul>
                <div class="panel-footer text-center" id="buttonPanel">
                    <button class="btn btn-danger" onclick="undo()">Undo</button>
                    <button class="btn btn-primary" onclick="saveRound(<?echo $game->getGameId()?>, this)">Save</button>
                </div> 
            </div>
        </div>

        <div class="col-sm-2" style="margin-top: 10px">
            <div class="panel panel-default" id="currentTarget">
                <div class="panel-heading">Current Target</div>
                <div class="panel-body">
                    <h1 class="text-center text-info fix-margin"><span class="next-target" id="nextTarget"><? echo $game->getNextTarget() ?></span></h1>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Throws on <span class="next-target" id="nextTargetText"><? echo $game->getNextTarget() ?></span></div>
                <div class="panel-body">
                    <h1 class="text-center text-info fix-margin" id="throwsOnTarget"><? echo $game->getThrowsOnTarget() ?></h1>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Total Throws</div>
                <div class="panel-body">
                    <h1 class="text-center text-info fix-margin" id="totalThrows"><? echo $game->getNumThrows()?></h1>
                </div>
            </div>

            <div class="panel panel-default" id="estimatedTotalWrapper">
                <div class="panel-heading">Estimated Total</div>
                <div class="panel-body">
                    <h1 class="text-center text-info fix-margin" id="estimatedTotal"><? echo round($game->getNumThrows()/($game-> getNextTarget() - 1) * 20)?></h1>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Streak</div>
                <div class="panel-body">
                    <h1 class="text-center text-info fix-margin" id="streak"><? echo $game->getStreakCount() ?></h1>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Throws
                    <a class="pull-right" href="">
                        <!--<span ng-if="showCumulativeThrows">Cumulative Throws</span>
                        <span ng-if="!showCumulativeThrows">Throws per Target</span>-->
                    </a>
                </div>
                <div class="panel-body">
                    <table class="table game">
                        <thead>
                            <tr>
                                <th>Target</th>
                                <? for ($i = 1; $i <= 20; $i++){ ?>
                                     <th class="text-center"><? echo $i ?></th>
                                <?}?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Throws</td>
                            <!--<tr ng-if="vm.bestGame" game-stats header-content="'best game'" game="vm.bestGame" cumulative-throws="showCumulativeThrows"></tr>
                            <tr game-stats header-content="'current game'" game="vm.game" cumulative-throws="showCumulativeThrows"></tr>-->
                            <?
                                $throwList = getThrowList($game);
                                for ($i = 1; $i <= 20; $i++){
                                    ?> <td class="text-center" id="target-<? echo $i?>"><? echo $throwList[$i] . "</td>";
                                }
                            ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
		<?
	}
include('../views/partials/footer.php');
}

?><script type="text/javascript" src="/eiriknf/dart/scripts/play-game.js"></script>