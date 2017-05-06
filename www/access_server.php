<!DOCTYPE html>
<html lang="en">
<head>
<?php include "includes/head.inc.php";?>
<title>DDNet Trashmap - Access Server</title>
</head>
<body>
<?php include "includes/openingBody.inc.php";?>

<?php
$data = json_decode(file_get_contents("/srv/trashmap/daemon_data.json"), true);
$config = $data["config"];
$servers = $data["storage"]["servers"];
?>

<div class="breadcrumbs">
    <div class="crumb">
        <a href=".">Main Page</a>
    </div>
    <div class="crumb">
        <a href="server_list.php">Server List</a>
    </div>
    <div class="crumb">
        Access Server        
    </div>
</div>

<div class="main">
    <section class="page_branding">
        <h2 class="page_title">DDNet Trashmap - Access Server</h2>
        <p class="page_description">
        <?php if(!in_array($_GET["id"], array_keys($servers))): ?>
            There is no server saved with the given identifier.
        <?php elseif(!password_verify($_GET["key"], $servers[$_GET["id"]]["accesskey"])): ?>
            The given accesskey does not match.
        <?php else:
            $displaycontent = true;
            $identifier = $_GET["id"];
            $info = $servers[$identifier]; ?>
            You can control your server on this site.
            Please add this link to your favorites and use it everytime you want to test a map.
            You can share this link with other players to give them access to the server.
            You can also add the teeworlds server to your favorites.
        </p>
        <?php endif; ?>
    </section>
    
    <?php if($displaycontent): ?>
    <section class="server_status_section">
        <h3 class="section_title">Server Status</h3>
        <p>
        Your server is <?php echo($info["running"] ? "running" : "offline"); ?> at the moment.
        <?php
            if($info["running"])
                echo("The ip of your server is 'timakro.de:".$info["port"]."' or '84.38.65.222:".$info["port"]."'.\n");
        ?>
        </p>
        <table class="data_table server_status_table">
        <?php
            if($info["running"]) {
                echo("<tr><th>Label</th><th>Port</th><th>Map</th><th>Rcon</th><th>Password</th><th>Playercount</th><th>Runtime</th></tr>\n");
                echo("<tr><td>".htmlentities($info["label"])."</td><td>".$info["port"]."</td><td>".htmlentities($info["mapname"])."</td><td>".($info["rcon"] == $config["defaultrcon"] ? "default" : "custom")."</td><td>".($info["password"] == null ? "false" : "true")."</td><td>".count($info["clientids"])." / ".$info["playerlimit"]."</td><td>".$info["runtimestring"]."</td></tr>\n");
            } else {
                echo("<tr><th>Label</th><th>Port</th><th>Map</th><th>Rcon</th><th>Password</th><th>Playercount</th></tr>\n");
                echo("<tr><td>".htmlentities($info["label"])."</td><td>".$info["port"]."</td><td>".htmlentities($info["mapname"])."</td><td>".($info["rcon"] == $config["defaultrcon"] ? "default" : "custom")."</td><td>".($info["password"] == null ? "false" : "true")."</td><td>- / ".$info["playerlimit"]."</td></tr>\n");
            }
        ?>
        </table>
    </section>

    <section>
        <h3 class="section_title"><?php echo($info["running"] ? "Stop" : "Start"); ?></h3>
        <p>
        Use this button to <?php echo($info["running"] ? "stop" : "start"); ?> the server.
        Later you can <?php echo($info["running"] ? "start" : "stop"); ?> it again on this site.
        </p>
        <form enctype="multipart/form-data" action="access_server_handle.php" method="POST">
            <input type="hidden" name="id" value="<?php echo($_GET["id"]); ?>">
            <input type="hidden" name="key" value="<?php echo($_GET["key"]); ?>">
            <input type="hidden" name="action" value="<?php echo($info["running"] ? "stop" : "start"); ?>">
            <input type="submit" value="<?php echo($info["running"] ? "Stop Server" : "Start Server"); ?>">
        </form>
    </section>

    <section class="map_section">
        <h3 class="section_title">Map</h3>
        <p>
        The map file to upload on the server.
        The maximum file size is <?php echo($config["mapsizehuman"]); ?>.
        The filename has to end with '.map'.
        If there are any critical characters in the filename it will be adjusted.
        If the uploaded file is no valid map the server can't run.
        </p>
        <form enctype="multipart/form-data" action="access_server_handle.php" method="POST">
            <input type="hidden" name="id" value="<?php echo($_GET["id"]); ?>">
            <input type="hidden" name="key" value="<?php echo($_GET["key"]); ?>">
            <input type="hidden" name="action" value="map">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo($config["mapsize"]); ?>">
            <input id="file_upload" type="file" name="map" class="inputfile" />
            <label for="file_upload" class="custom_file_upload">
                <span>Upload Map</span>
            </label>
            <input type="submit" value="Change Map">
        </form>
    </section>

    <section>
        <h3 class="section_title">Password</h3>
        <p>
        Change the password required to join the server.
        The maximum length is <?php echo($config["maxlengthpassword"]); ?> characters.
        Leave this field empty to open the server for everybody.
        </p>
        <form enctype="multipart/form-data" action="access_server_handle.php" method="POST">
            <input type="hidden" name="id" value="<?php echo($_GET["id"]); ?>">
            <input type="hidden" name="key" value="<?php echo($_GET["key"]); ?>">
            <input type="hidden" name="action" value="password">
            <input type="text" name="password" maxlength="<?php echo($config["maxlengthpassword"]); ?>">
            <br>
            <input type="submit" value="Change Password">
        </form>
    </section>

    <section>
        <h3 class="section_title">Rcon</h3>
        <p>
        Change the rcon password required to access the server console ingame.
        The maximum length is <?php echo($config["maxlengthrcon"]); ?> characters.
        A list of allowed commands in the rcon console can be found <a href="rcon_commands.php">here</a>.
        If this value is empty or too long the default rcon password '<?php echo($config["defaultrcon"]); ?>' will be used.
        </p>
        <form enctype="multipart/form-data" action="access_server_handle.php" method="POST">
            <input type="hidden" name="id" value="<?php echo($_GET["id"]); ?>">
            <input type="hidden" name="key" value="<?php echo($_GET["key"]); ?>">
            <input type="hidden" name="action" value="rcon">
            <input type="text" name="rcon" maxlength="<?php echo($config["maxlengthrcon"]); ?>" value="<?php echo($config["defaultrcon"]); ?>">
            <br>
            <input type="submit" value="Change Rcon">
        </form>
    </section>

    <section>
        <h3 class="section_title">Playerlimit</h3>
        <p>
        Change the maximum number of players on the server.
        The number has to be between <?php echo($config["minplayers"]); ?> and <?php echo($config["maxplayers"]); ?>.
        If this value isn't valid the default value <?php echo($config["defaultplayers"]); ?> will be used.
        This can only be changed if the server is offline.
        </p>
        <form enctype="multipart/form-data" action="access_server_handle.php" method="POST">
            <input type="hidden" name="id" value="<?php echo($_GET["id"]); ?>">
            <input type="hidden" name="key" value="<?php echo($_GET["key"]); ?>">
            <input type="hidden" name="action" value="playerlimit">
            <input type="number" name="playerlimit" value="<?php echo($config["defaultplayers"]); ?>" min="<?php echo($config["minplayers"]); ?>" max="<?php echo($config["maxplayers"]); ?>">
            <br>
            <input type="submit" value="Change Playerlimit">
        </form>
    </section>

    <section>
        <h3 class="section_title">Delete</h3>
        <p>
        Use this button to delete the server.
        You can't recover the server after it has been deleted.
        </p>
        <form enctype="multipart/form-data" action="access_server_handle.php" method="POST">
            <input type="hidden" name="id" value="<?php echo($_GET["id"]); ?>">
            <input type="hidden" name="key" value="<?php echo($_GET["key"]); ?>">
            <input type="hidden" name="action" value="delete">
            <input type="submit" value="Delete Server">
        </form>
    </section>
    <?php endif;?>
</div>
</body>
</html>