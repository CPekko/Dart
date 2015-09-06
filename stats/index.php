<?php
date_default_timezone_set("Europe/Oslo");
require_once('../partials/classes.php');
require_once('../partials/funcs.php');
include('../views/partials/head.php');
if (isset($_GET['id'])){
    $playerId = $_GET['id'];
    $player = getPlayer($playerId);
    $numberOfGames = getNumberOfGames($player);
    $arrowsThrown = getArrowsThrown($player);
    $targetHits = getTargetHits($player);
    $longestStreak = getLongestStreak($player);
    $bestGame = getBestGame($player);
    $worstGame = getWorstGame($player);
    $averageGame = getAverageGame($player);
    $averageLast10 = getAverageLast10($player);
    ?>
    <div class="container body-content">
        <section>
            <h2>Stats for <? echo $player->getName()?></h2>
            <div class="row" id="stats" data-user-name="<?echo $player->getName()?>" data-user-id="<?echo $playerId?>">
                <div class="col-lg-4 col-md-12 split-small">
                    <div class="panel panel-default">
                        <div class="panel-heading">Stats</div>
                        <table class="table">
                            <tr>
                                <td>Games Played</td>
                                <td><?echo $numberOfGames?> games</td>
                            </tr>
                            <tr>
                                <td>Arrows Thrown</td>
                                <td><?echo $arrowsThrown?> throws</td>
                            </tr>
                            <tr>
                                <td>Longest Streak</td>
                                <td><?echo $longestStreak?> throws</td>
                            </tr>
                            <tr>
                                <td>Hit Ratio</td>
                                <td><?echo round($targetHits/$arrowsThrown*100)?>%</td>
                            </tr>
                        </table>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">Games</div>
                        <table class="table">
                            <tr>
                                <td>Best Game</td>
                                <td><?echo $bestGame->getNumThrows()?> throws</td>
                            </tr>
                            <tr>
                                <td>Worst Game</td>
                                <td><?echo $worstGame->getNumThrows()?> throws</td>
                            </tr>
                            <tr>
                                <td>Average Game</td>
                                <td><?echo round($averageGame)?> throws</td>
                            </tr>
                            <tr>
                                <td>Average Last 10</td>
                                <td><?echo round($averageLast10)?> throws</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-lg-8 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?echo $player->getName()?>'s Best Game
                            <span class="pull-right">Finished <?echo date('d.m.Y k\l. H:m',strtotime($bestGame->getTimeFinished()));?></span>
                        </div>
                        <div class="panel-body">
                            <table class="table game" id="bestGame">
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
                                        $throwList = getThrowList($bestGame);
                                        for ($i = 1; $i <= 20; $i++){
                                            ?> <td class="text-center" id="target-<? echo $i?>"><? echo $throwList[$i] . "</td>";
                                        }
                                    ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Per Target Hit Ratio</div>
                        <div class="panel-body">
                            <highchart id="hitRatioChart"></highchart>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Accuracy Histogram</div>
                        <div class="panel-body">
                            <highchart id="accuracyChart"></highchart>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Streak Length Histogram</div>
                        <div class="panel-body">
                            <highchart id="streakChart"></highchart>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Total Hits per Target</div>
                        <div class="panel-body">
                            <highchart id="hitTargetChart"></highchart>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
<? } 
include('../views/partials/footer.php');
?>
<script src="/eiriknf/dart/scripts/highcharts.js"></script>
<script src="/eiriknf/dart/scripts/charts.js"></script>
