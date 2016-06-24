<?php
date_default_timezone_set("Europe/Oslo");
require_once('../../partials/classes.php');
require_once('../../partials/funcs.php');
include('../../views/partials/head.php');
if(isset($_GET['id'])){
	$game = getGame($_GET['id']);
    $bestGame = getBestGame($game->getPlayer());
    $average = round(getAverageGame($game->getPlayer()));
	?>
	 <div class="container body-content">
		<section>
		    <h3 class="player-name" style="margin: 40px 0"><img src="<?php echo $game->getPlayer()->getImage()?>"><?php echo $game->getPlayer()->getName() ?>'s Game</h3>
			<div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Game finished in <b><?php echo sizeof($game->getThrows())?></b> throws
                            <span class="pull-right">Finished <?php echo date('d.m.Y k\l. H:i',strtotime($game->getTimeFinished()));?></span>
                        </div>
                        <div class="panel-body">
                            <table class="table game">
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
                                        $throwList = getThrowList($game);
                                        $bestThrowList = getThrowList($bestGame);
                                        $highchartArray = array();
                                        $highchartArray2 = array();
                                        $highchartArray3 = array();
                                        for ($i = 1; $i <= 20; $i++){
                                            $highchartArray[] = array($i, $throwList[$i]);
                                            $highchartArray2[] = array($i, $throwList[$i] + $highchartArray2[$i-2][1]);
                                            ?> <td class="text-center" id="target-<?php echo $i?>"><?php echo $throwList[$i] . "</td>";
                                        }
                                        ?></tr><tr><td>Best Game</td> <?php
                                        for ($i = 1; $i <= 20; $i++){
                                            $highchartArray3[] = array($i, $bestThrowList[$i] + $highchartArray3[$i-2][1]);
                                            ?> <td class="text-center" id="target-<?php echo $i?>"><?php echo $bestThrowList[$i] . "</td>";
                                        }
                                    ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">This Game vs Best Game</div>
                        <div class="panel-body">
                            <highchart id="totalThrows"></highchart>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Throws per Target</div>
                        <div class="panel-body">
                            <highchart id="hitsPerTarget"></highchart>
                        </div>
                    </div>
                </div>
            </div>
		</section>
	</div>
<?php } 
include('../../views/partials/footer.php'); ?>
<script src="/eiriknf/dart/scripts/highcharts.js"></script>
<script>
$( document ).ready(function() {
    $('#hitsPerTarget').highcharts({
        title: true,
        credits: {
            enabled: false
        },
        legend: {
            enabled: false
        },
        tooltip: {
            formatter: function () {
                if(this.y === 1) return ' Used <b>' + this.y + '</b> throw on <b>' + this.x + '</b>';
                return ' Used <b>' + this.y + '</b> throws on <b>' + this.x + '</b>';
            }
        },
        xAxis: {
            title: {
                text: 'Target'
            },
            categories: ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'],
            labels:{
                step: 1
            }
        },
        yAxis: {
            title: {
                text: 'Throws'
            }
        },
        loading: true,
        series: [{
            data: <?php echo json_encode($highchartArray)?>
        }]
    });

    $('#totalThrows').highcharts({
        title: true,
        credits: {
            enabled: false
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        tooltip: {
            headerFormat:  '',
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> throws to <b>{point.x}</b><br/>',
            shared: true
        },
        plotOptions: {
            line:{
                marker: {
                    enabled: false
                }
            }
        },
        xAxis: {
            title: {
                text: 'Target'
            },
            categories: ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'],
            labels:{
                step: 1
            }
        },
        yAxis: {
            title: {
                text: 'Throws'
            },
            min: 0,
            plotLines: [{
                value: <?php echo $average?>,
                color: 'red',
                width: 2,
                label: {
                    text: 'Average Game - <?php echo $average?>',
                    y: 16
                },
                verticalAlign: 'bottom',
                zIndex: 5
            }]
        },
        loading: true,
        series: [{
            name: 'This Game',
            data: <?php echo json_encode($highchartArray2)?>
        }, {
            name: 'Best Game',
            data: <?php echo json_encode($highchartArray3)?>
        }]
    });
});
</script>