<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");
include("function.php");
if ($_SESSION["erg"] == $_POST['erg'])
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
else
{
	?>
	Bitte die Rechnung lösen um eine IP zu assignen:<br />
	<?php
	echo captcha();
	?>
	<form action="index.php" method="post">
	<input type="text" size="17" name="erg">
	<input type="submit" value="assign">
	</form>
<?php } ?>
