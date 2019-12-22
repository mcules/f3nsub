<?php
include("config.php");
include("function.php");

$ips = getAnnouncements();

$time = time();
$dbh->query("UPDATE ip SET act_announce = '0'");
$query = $dbh->query("SELECT * FROM ip WHERE assign = '1'");
$result = $query->fetchAll(PDO::FETCH_OBJ);

foreach ($result as $row) {
        if(in_array($row->ip, $ips)) {
                $dbh->query("UPDATE ip SET last_announce = '$time', act_announce = '1' WHERE ip = '$row->ip'");
        }
}

