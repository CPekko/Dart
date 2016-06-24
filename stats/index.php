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
    $hitRatio = getHitRatio($player->getPlayerId());
    $dartboard = array(19,7,16,8,11,14,9,12,5,20,1,18,4,13,6,10,15,2,17,3);
    ?>
    <div class="container body-content">
        <section>
            <h2>Stats for <?php echo $player->getName()?></h2>
            <div class="row" id="stats" data-user-name="<?php echo $player->getName()?>" data-user-id="<?php echo $playerId?>">
                <div class="col-lg-4 col-md-12 split-small">
                    <div class="panel panel-default">
                        <div class="panel-heading">Stats</div>
                        <table class="table">
                            <tr>
                                <td>Games Played</td>
                                <td><?php echo $numberOfGames?> games</td>
                            </tr>
                            <tr>
                                <td>Arrows Thrown</td>
                                <td><?php echo $arrowsThrown?> throws</td>
                            </tr>
                            <tr>
                                <td>Longest Streak</td>
                                <td><?php echo $longestStreak?> throws</td>
                            </tr>
                            <tr>
                                <td>Hit Ratio</td>
                                <td><?php echo round($targetHits/$arrowsThrown*100)?>%</td>
                            </tr>
                        </table>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">Games</div>
                        <table class="table">
                            <tr>
                                <td>Best Game</td>
                                <td><?php echo $bestGame->getNumThrows()?> throws</td>
                            </tr>
                            <tr>
                                <td>Worst Game</td>
                                <td><?php echo $worstGame->getNumThrows()?> throws</td>
                            </tr>
                            <tr>
                                <td>Average Game</td>
                                <td><?php echo round($averageGame)?> throws</td>
                            </tr>
                            <tr>
                                <td>Average Last 10</td>
                                <td><?php echo round($averageLast10)?> throws</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-lg-8 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $player->getName()?>'s Best Game
                            <span class="pull-right">Finished <?php echo date('d.m.Y k\l. H:i',strtotime($bestGame->getTimeFinished()));?></span>
                        </div>
                        <div class="panel-body">
                            <table class="table game" id="bestGame">
                                <thead>
                                    <tr>
                                        <th>Target</th>
                                        <?php for ($i = 1; $i <= 20; $i++){ ?>
                                             <th class="text-center"><?php echo $i ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Throws</td>
                                    <!--<tr ng-if="vm.bestGame" game-stats header-content="'best game'" game="vm.bestGame" cumulative-throws="showCumulativeThrows"></tr>
                                    <tr game-stats header-content="'current game'" game="vm.game" cumulative-throws="showCumulativeThrows"></tr>-->
                                    <?php
                                        $throwList = getThrowList($bestGame);
                                        for ($i = 1; $i <= 20; $i++){
                                            ?> <td class="text-center" id="target-<?php echo $i?>"><?php echo $throwList[$i] . "</td>";
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
                            <div class="board">
                                <a style="position: absolute; left: 0; right:0; top:0; bottom:0;" > </a>
                                <div class="ring double">
                                <?php foreach ($dartboard as $i => $j){ ?>
                                    <a class="piece piece-<?php echo $i+1?>" ></a>
                                <?php } ?>
                                </div>
                                <div class="ring outer">
                                <?php foreach ($dartboard as $i => $j){ ?>
                                    <a class="piece piece-<?php echo $i+1?>" ></a>
                                <?php } ?>
                                </div>
                                <div class="ring triple">
                                <?php foreach ($dartboard as $i => $j){ ?>
                                    <a class="piece piece-<?php echo $i+1?>" ></a>
                                <?php } ?>
                                </div>
                                <div class="ring inner">
                                <?php foreach ($dartboard as $i => $j){ ?>
                                    <a class="piece piece-<?php echo $i+1?>" ></a>
                                <?php } ?>
                                </div>
                                <div class="numbers">
                                <?php foreach ($dartboard as $i => $j){ ?>
                                     <a class="number number-<?php echo $i+1?>" ><?php echo $j?></a>
                                <?php } ?>
                                </div>
                                <div class="stats">
                                <?php foreach ($hitRatio as $j){ ?>
                                     <a class="stat stat-<?php echo $j[0]?>" ><?php echo round($j[1])?>%</a>
                                <?php } ?>
                                </div>
                                <a class="bull ring" ></a>
                                <a class="red-bull ring" ></a>
                            </div>
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
                    <div class="panel panel-default">
                        <div class="panel-heading">Per Target Hit Ratio Column Diagram</div>
                        <div class="panel-body">
                            <highchart id="hitRatioChart"></highchart>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
<?php } 
include('../views/partials/footer.php');
?>
<script src="/eiriknf/dart/scripts/highcharts.js"></script>
<script src="/eiriknf/dart/scripts/charts.js"></script>
