<?php
date_default_timezone_set("Europe/Oslo");
require_once('partials/classes.php');
require_once('partials/funcs.php');
include('views/partials/head.php');
$activeGames = array();

?>
 <div class="container body-content">
    <section>
        <h2>Games</h2>
        
        <div class="row">
            <?
            foreach(getActivePlayers() as $player){
                ?>
                <div class="col-sm-4">
                    <h3 class="player-name"><img src="<?echo $player->getImage()?>"><? echo $player->getName() ?></h3>
                    <?
                        $activeGame = getActiveGame($player);
                        if(isset($activeGame)){
                            $activeGames[] = $activeGame;
                            $throws = $activeGame->getThrows();
                    ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Game Started <span data-time="<? echo $activeGame->getTimeStarted() ?>"></span></h4>
                            </div>
                            <div class="list-group">
                                <div class="list-group-item"> <? echo sizeof($throws) ?> throws</div>
                                <div class="list-group-item">Next target is <? echo $activeGame->getNextTarget() ?> </div>
                            </div>
                            <div class="panel-footer">
                                <a class="btn btn-primary" onclick="playRound(<? echo $activeGame->getGameId() ?>,<? echo $player->getPlayerId() ?>)">Play Round</a>
                                <a class="btn btn-danger" onclick="abandonGame(<? echo $activeGame->getGameId() ?>,<? echo $player->getPlayerId() ?>)">Abandon Game</a>
                            </div>
                        </div>
                    <?
                        } else {
                    ?>
                            <p>
                                <button class="btn btn-primary" onclick="newGame(<? echo $player->getPlayerId() ?>)">New Game</button>
                                <button class="btn btn-primary" onclick="window.location.href='/eiriknf/dart/stats/?id=<?echo $player->getPlayerId()?>'">View Stats</button>
                            </p>
                    <?}?>
                </div>
            <?
            }
            ?>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Active Games</div>
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
                                <?
                                foreach ($activeGames as $game) {
                                    ?> <tr> <td> <? echo $game->getPlayer()->getName();
                                    $throwList = getThrowList($game);
                                    for ($i = 1; $i <= 20; $i++){
                                        ?> <td class="text-center" id="target-<? echo $i?>"><? echo $throwList[$i] . "</td>";
                                    }
                                }
                                ?>
                            </tr></td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="scripts/dashboard.js"></script>
<?
include('views/partials/footer.php');
?>