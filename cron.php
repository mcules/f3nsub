<?php
include("config.php");
include("function.php");

$ips = getAnnouncements();

$time = time();
$dbh->query("UPDATE ip SET act_announce = '0'");

foreach($ips as $ip) {
        $dbh->query("UPDATE ip SET last_announce = '$time', act_announce = '1' WHERE ip = '$ip'");
}
