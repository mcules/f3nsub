<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");
require __DIR__.'/vendor/autoload.php';
if ($initaktiv == 1)
{
	$excluded = IPTools\Network::parse($_GET['subnet'])->moveTo($_GET['size']);
	foreach($excluded as $network) {
        	echo (string)$network . '<br>';
		$eintrag = "INSERT INTO ip (ip, assign, last_announce) VALUES ('$network', '0', '0')";
		$eintragen = mysqli_query($db, $eintrag);
	}

}
else
{
	echo 'Bitte $initaktiv in config.php auf 1 setzen';
}
?>
