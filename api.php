<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include("config.php");
include("function.php");
$data = array();
if ($_GET['abfrage'] == "all")
{
	$ergebnis = mysqli_query($db, "SELECT * FROM ip WHERE assign = '1'");
	while($row = mysqli_fetch_object($ergebnis))
	{
		$datas = array();
		$datas['ip'] = $row->ip;
		$datas['act_announce'] = $row->act_announce;
		$datas['last_announce'] = $row->last_announce;
		array_push($data, $datas);
	}
}
if ($_GET['abfrage'] == "aktiv")
{
        $ergebnis = mysqli_query($db, "SELECT * FROM ip WHERE assign = '1' AND act_announce = '1'");
        while($row = mysqli_fetch_object($ergebnis))
        {
                $datas = array();
                $datas['ip'] = $row->ip;
                $datas['act_announce'] = $row->act_announce;
                $datas['last_announce'] = $row->last_announce;
                array_push($data, $datas);
        }
}
if ($_GET['abfrage'] == "inaktiv")
{
        $ergebnis = mysqli_query($db, "SELECT * FROM ip WHERE assign = '1' AND act_announce = '0'");
        while($row = mysqli_fetch_object($ergebnis))
        {
                $datas = array();
                $datas['ip'] = $row->ip;
                $datas['act_announce'] = $row->act_announce;
                $datas['last_announce'] = $row->last_announce;
                array_push($data, $datas);
        }
}
if ($_GET['abfrage'] == "abgelaufen")
{
	$abgelaufen=time()-$abgelaufen_zeit;
        $ergebnis = mysqli_query($db, "SELECT * FROM ip WHERE assign = '1' AND act_announce = '0' AND last_announce < '$abgelaufen' AND last_announce > '1'");
        while($row = mysqli_fetch_object($ergebnis))
        {
                $datas = array();
                $datas['ip'] = $row->ip;
                $datas['act_announce'] = $row->act_announce;
                $datas['last_announce'] = $row->last_announce;
                array_push($data, $datas);
        }
}
if ($_GET['abfrage'] == "nieaktiv")
{
        $abgelaufen=time()-$abgelaufen_zeit;
        $ergebnis = mysqli_query($db, "SELECT * FROM ip WHERE assign = '1' AND act_announce = '0' AND last_announce = '0'");
        while($row = mysqli_fetch_object($ergebnis))
        {
                $datas = array();
                $datas['ip'] = $row->ip; 
                $datas['act_announce'] = $row->act_announce;
                $datas['last_announce'] = $row->last_announce;
                array_push($data, $datas);
        }
}
header("Content-Type: application/json");
echo json_encode($data, JSON_PRETTY_PRINT);
//print_r($data);
?>
