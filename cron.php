<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");
include("function.php");
$time=time();
$ergebnis = mysqli_query($db, "SELECT * FROM ip WHERE assign = '1'");
while($row = mysqli_fetch_object($ergebnis))
{
	if(getrout($row->ip))
	{
		$aendern = "UPDATE ip Set last_announce = '$time' WHERE id = '$row->id'";
		$update = mysqli_query($db, $aendern);
	}
}

?>
