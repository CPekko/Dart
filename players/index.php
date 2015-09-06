 <?php
date_default_timezone_set("Europe/Oslo");
require_once('../partials/classes.php');
require_once('../partials/funcs.php');
include('../views/partials/head.php');
$players = getAllPlayers();
 ?>
 <div class="container body-content">
    <section>
        <h2>Players</h2>

        <p>
            <a href="edit">New Player</a>
        </p>
        <table class="table">
            <tr>
                <th>
                    Name
                </th>
                <th></th>
            </tr>
            <? foreach ($players as $player) {?>
                <tr <? if (! $player->isActive()) echo 'class="inactive"' ?>>
                    <td class="player-name">
                        <img src="<?echo $player->getImage()?>"/>
                        <? echo $player->getName() ?>
                    </td>
                    <td class="text-right">
                        <a href="/eiriknf/dart/stats?id=<? echo $player->getPlayerId() ?>">Stats</a>
                        <a href="edit?id=<? echo $player->getPlayerId() ?>">Edit</a>
                        <!--<a href="/eiriknf/dart/players/delete?id=<? echo $player->getPlayerId() ?>">Delete</a>-->
                    </td>
                </tr>
            <?}?>

        </table>
    </section>
</div>

<? include('../views/partials/footer.php');