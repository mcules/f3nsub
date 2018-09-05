<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


$mysqluser="f3nsub";
$mysqlpw="";
$dbname="f3nsub";

$db = mysqli_connect("localhost", $mysqluser, $mysqlpw, $dbname);
if(!$db)
{
  exit("Verbindungsfehler: ".mysqli_connect_error());
}

$initaktiv=0;
$abgelaufen_zeit=365*24*60*60; //In Sekunden

?>
