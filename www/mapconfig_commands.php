<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="stylesheet.css">
<title>DDNet Trashmap - Mapconfig Commands</title>
</head>
<body>
<?php
$data = json_decode(file_get_contents("/srv/trashmap/daemon_data.json"), true);
$config = $data["config"];
?>

<a href=".">Main Page</a> -> <a href="create_server.php">Create Server</a> -> Mapconfig Commands
<h2>DDNet Trashmap - Mapconfig Commands</h2>
<p>
When uploading a mapconfig only specifically allowed commands can be used in it.
If forbidden commands are used in a mapconfig they will be removed and a warning will be displayed.
</p>

<h3>Allowed Commands</h3>
<p>
This is a list of all allowed commands in a mapconfig.
</p>
<ul>
<?php
foreach($config["allowed_commands"] as $command)
    echo("<li>".$command."</li>\n");
?>
</ul>

</body>
</html>
