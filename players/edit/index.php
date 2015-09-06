<?php
date_default_timezone_set("Europe/Oslo");
require_once('../../partials/classes.php');
require_once('../../partials/funcs.php');
include('../../views/partials/head.php');
 ?>
 <div class="container body-content">
    <section>
<? 
if (isset($_GET['name']) && isset($_GET['active']) && isset($_GET['url'])){
    $name = $_GET['name'];
    $active = $_GET['active'];
    $image = $_GET['url'];
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $player = new Player($id, $name, $active);
        if (! is_null($image) && ! $image == ""){
            $player->setImage($image);
        }
        updatePlayer($player);
    } else {
        $player = new Player (0, $name, $active);
        createPlayer($player);
    }
    header("Location: http://folk.ntnu.no/eiriknf/dart/players");
    die();
}

if(isset ($_GET['id'])){
    $player = getPlayer($_GET['id']);
?>
        <h2>Edit <? echo $player->getName() ?></h2>
        
        <form class="row">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-md-2" for="name">Name</label>
                    <div class="col-md-10">
                        <input type="text" value="<? echo $player->getName(); ?>" class="form-control" id="name" data-player-id="<?echo $player->getPlayerId();?>" autofocus="autofocus" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2" for="name">Image URL</label>
                    <div class="col-md-10">
                        <input type="text" value="<? echo $player->getImage(); ?>" class="form-control" id="url" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2" for="active">Active</label>
                    <div class="col-md-10">
                        <input type="checkbox" <?if ($player->isActive()) echo "checked"; ?> id="active" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <button class="btn btn-primary" onclick="save(event)">Save</button>
                        <button class="btn btn-danger" onclick="cancel(event)">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
<? } else { ?>
        <h2>New player</h2>
        
        <form class="row">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-md-2" for="name">Name</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="name" autofocus="autofocus" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2" for="name">Image URL</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="url" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2" for="active">Active</label>
                    <div class="col-md-10">
                        <input type="checkbox" checked id="active" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <button class="btn btn-primary" onclick="save(event)">Save</button>
                        <button class="btn btn-danger" onclick="cancel(event)">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
<? } ?>
    </section>
</div>
<script src="/eiriknf/dart/scripts/edit-player.js"></script>

<? include('../../views/partials/footer.php'); ?>