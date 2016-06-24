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
            <?php foreach ($players as $player) {?>
                <tr <?php if (! $player->isActive()) echo 'class="inactive"' ?>>
                    <td class="player-name">
                        <img src="<?php echo $player->getImage()?>"/>
                        <?php echo $player->getName() ?>
                    </td>
                    <td class="text-right">
                        <a href="/eiriknf/dart/stats?id=<?php echo $player->getPlayerId() ?>">Stats</a>
                        <a href="edit?id=<?php echo $player->getPlayerId() ?>">Edit</a>
                        <!--<a href="/eiriknf/dart/players/delete?id=<?php echo $player->getPlayerId() ?>">Delete</a>-->
                    </td>
                </tr>
            <?php } ?>

        </table>
    </section>
</div>

<?php include('../views/partials/footer.php');