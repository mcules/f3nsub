<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");
include("function.php");
$time=time();
$aendern = "UPDATE ip Set act_announce = '0'";
$update = mysqli_query($db, $aendern);
$ergebnis = mysqli_query($db, "SELECT * FROM ip WHERE assign = '1'");
while($row = mysqli_fetch_object($ergebnis))
{
	if(getrout($row->ip))
	{
		$aendern = "UPDATE ip Set last_announce = '$time', act_announce = '1' WHERE id = '$row->id'";
		$update = mysqli_query($db, $aendern);
	}
}

?>
