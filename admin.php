<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");
include("function.php");
$ergebnis = mysqli_query($db, "SELECT * FROM ip WHERE assign = '1'");
echo "Die aktuellen Announcements sowie last_announce wird nur 1x täglich aktualisiert";
echo "<table><tr><td>ip</td><td>last_announce</td>";
while($row = mysqli_fetch_object($ergebnis))
{
	$datum = date("d.m.Y",$row->last_announce);
	$uhrzeit = date("H:i",$row->last_announce);
	$zeit = ''.$datum.' - '.$uhrzeit.' Uhr';
	if($row->act_announce == 1)
	{
		echo '<tr><td>'.$row->ip.'</td><td>'.$zeit.'</td><td>Wird aktuell announced</td></tr>';
	}
	else
	{
		echo '<tr><td>'.$row->ip.'</td><td>'.$zeit.'</td><td>Wird aktuell nicht announced</td></tr>';
	}
}

?>
