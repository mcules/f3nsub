<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");
if ($_GET['assign'] == true)
{
	$ergebnis = mysqli_query($db, "SELECT ip FROM ip WHERE assign = '0' LIMIT 0,1");
	while($row = mysqli_fetch_object($ergebnis))
	{
		$ip = $row->ip;
	}
	echo "Du hast folgendes IP Netz bekommen: $ip <br /> <br />";
	$aendern = "UPDATE ip Set assign = '1' WHERE ip = '$ip'";
	$update = mysqli_query($db, $aendern);
}
?>
Klicke <a href="index.php?assign=true">hier</a> um eine IP Adresse zu assignen
