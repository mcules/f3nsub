<?php
session_start();
include("config.php");
include("function.php");

if(!isset($_POST["erg"]) && !isset($_POST["send"])) {
	echo "Bitte die Rechnung l&ouml;sen um eine IP zu assignen:<br/>";
	echo captcha();
	echo '<form action="index.php" method="post">
			  <input type="text" size="17" name="erg">
			  <input type="hidden" size="17" name="send" value="true">
			  <input type="submit" value="assign">
		  </form>';
}
elseif ($_SESSION["erg"] == $_POST['erg'] && $_POST['send'] == true) {
	$query = $dbh->query("SELECT ip FROM ip WHERE assign = '0' ORDER BY RAND() LIMIT 1");
	$ip = $query->fetch();
	$ip = $ip[0];
	if($ip) {
		$dbh->query("UPDATE ip SET assign='1' WHERE ip='$ip'");
		echo "Du hast folgendes IP Netz bekommen: $ip <br /> <br />";
	}
	else {
		echo "Sorry, keine Subnetze mehr verfügbar";
	}
	session_destroy();
}
else {
	die("Falsch gerechnet?");
}
?>
Bitte folgendes beachten:<br>
* IP Adressen werden aus Datenschutzgründen anonym vergeben<br>
* Da für mich kein Ansprechpartner vorhanden ist, werde ich News oder Probleme auf dieser Webseite veröffentlichen. Es ist daher sinnvoll diese Website im Blick zu behalten.<br>
* IP Adressen die 180 Tage nicht genutzt werden, werden wieder freigegeben sofern sie nicht im Babel announced werden.<br>
* Für das Routing bist du selbst verantwortlich, ich kann nur rudimentär Hilfestellung geben<br>
* Auf den Servern die unter meiner Kontrolle stehen, wird (demnächst) ein Simpel-Babelweb angeboten so das Routingprobleme selbstständig diagnostiziert werden können. Hier findest du <a href="https://lookingglass.itstall.de/">Lookingglass</a>.<br>

* Solltest du der Meinung sein, der Fehler liegt auf meiner Seite kontaktiere mich bitte unter fff@roadit.de mit einer genauen Fehlerbeschreibung<br>
* Pro Hood sollte ein eigenes /64 hier geklickt werden. Es sind genug da, also gerne auch mehrere klicken wenn nötig. Bitte kein NAT o.ä. foo machen. Sollten mehrere Subnetze benötigt werden, melde dich bei mir per <a href="mailto:fff@roadit.de">Email</a><br>
* Es gibt keine eingehende Firewall auf den Border Gateways. Alle Clients sind aus dem Internet direkt erreichbar. Wenn dies nicht gewünscht ist muss auf dem Freifunk Gateway eine Firewall installiert werden die eingehende Verbindungen blockiert<br>
* Immer darauf achten, das möglichst alle Router auf dem Weg mit einer IPv6 im traceroute antworten können. Zumindest vor und nach einem Tunnel (wo die MTU kleiner wird) ist es Pflicht damit PMTU einwandfrei funktioniert.<br> 
<!-- * <a href="https://network.cdresel.de/abuse-kontakt/">Abusehandling</a><br>*/ -->
<br>
<b>Einrichten der Adresse für Freifunk Franken auf einen zentralen Gateway:</b><br>
Nachdem du die Adresse hier erhalten hast, musst du folgendes auf deinem Gateway tun (ausgehend von einer Debian Standartkonfiguration, bei anderen Systemen sieht es u.U. etwas anders aus):<br>
Zuerst die Babel config anpassen, folgende Zeilen in die Filterregeln hinzufügen:<br>
<pre>redistribute local ip 2a0c:b642:1030::/48
redistribute ip 2a0c:b642:1030::/48
</pre>
Das Interface für die Clients (in einem Batmannetz meist batX) braucht eine v6 Adresse aus dem Netzbereich, daher diese Zeile an das Interface mit ran binden:
<pre>post-up ip -6 addr add 2a0c:b642:1030:xxxx::1/64 dev batX
</pre>
OWN_SUBNET durch das Subnetz das du hier erhalten hast ersetzen, also z.b. 2a0c:b642:1030:xxxx::1/64<br>
Ebenfalls ist eine Route nötig die du ins Babel announcen musst, damit meine Bordergateways wissen wohin sie die Pakete eigentlich schicken sollen, auch dies kann direkt mit an das Interface gebunden werden:
<pre>post-up ip -6 route add 2a0c:b642:1030:xxxx::/64 dev $IFACE proto static tab fff
</pre>
Durch die oben eingefügte Babelsettings sollte die Route nun im Babel announced werden. Sobald das der Fall ist, wird innerhalb der nächsten Minute automatisch auch meine Border Gateways eine default route im Babel announcen, dies kann man prüfen indem man eingibt:
<pre>ip -6 ro sh tab fff | grep 2a0c:b642:1030:xxxx
</pre>
Als Ausgabe sollte dann etwa folgendes raus kommen:
<pre>default from 2a0c:b642:1030:xxxx::/64 via fe80::5054:ff:fe66:c48f dev ens9 proto babel metric 1024  pref medium
2a0c:b642:1030:xxxx::/64 dev bat2 proto static metric 1024  pref medium
</pre>
Die erste Route kommt von meinen Border Gateways (damit dein Gateway weiß wohin es die Daten schicken soll) und die 2 ist von dir (damit wissen meine Border Gateways wohin sie die Daten schicken müssen).<br>
Danach nur noch den radvd anpassen, damit die Clients auch eine v6 sich nehmen können. In /etc/radvd.conf bei der entsprechenden Hood folgendes ändern:
<pre>AdvDefaultLifetime 600;
</pre>
Damit wird den Clients eine default Route gegeben jetzt nur noch das Subnetz hinzufügen (genauso wie schon das fd43 als neuer Abschnitt):
<pre>prefix OWN_SUBNET::/64 {
	AdvOnLink on;
	AdvAutonomous on;
	};
</pre>
die ganze config für die Hood kann also z.b. so aussehen:
<pre>interface bat2 {
        AdvSendAdvert on;
        MinRtrAdvInterval 60;
        MaxRtrAdvInterval 300;
        AdvDefaultLifetime 600;
        AdvRASrcAddress {
                fe80::5054:ff:xx:xx;
        };
        prefix fd43:5602:29bd:xx::/64 {
                AdvOnLink on;
                AdvAutonomous on;
        };
        prefix 2a0c:b642:1030:xxxx::/64 {
                AdvOnLink on;
                AdvAutonomous on;
        };
        route fc00::/7 {
        };
};
</pre>
<b>Dezentrales Gateway</b><br>
Sollte es sich um ein dezentrales Gateway mit der Gatewayfirmware von Fabian handeln (auf Adrian habe ich es noch nicht getestet sollte aber wohl genauso funktionieren) ist das Setup deutlich leichter. Es muss nur in der /etc/config/gateway unter config client eine v6 Adresse aus dem Subnetz mit der Option ip6addr eingetragen werden, z.b. so:
<pre>config client
        option vlan '1'
        option ipaddr '10.50.xxx.1/24'
        option ip6addr '2a0c:b642:1030:xxxx::1/64'
        option dhcp_start '10.50.xxx.127'
</pre>
danach das configuregateway Script neu aufrufen. Anschließend müssen zum aktuellen Zeitpunkt noch die Filterregeln im Babel erweitert werden, dazu in /etc/config/babeld folgende Blöcke hinzufügen:
<pre>config filter
        option type 'redistribute'
        option local 'true'
        option ip '2a0c:b642:1030::/48'
[...]
config filter
        option type 'redistribute'
        option ip '2a0c:b642:1030::/48'
</pre>
Einmal Babel neu starten (/etc/init.d/babeld restart oder einfach Gerät neu starten) und schon sollte v6 in der dezentralen Hood laufen<br>
Aus Sicherheitsgründen empfehle ich, die öffentliche v6 Adresse die von nun an an br-mesh hängt, nicht an das öffentliche Monitoring zu senden. Dazu am dezentralen Gateway untern /etc/config/nodewatcher br-mesh aus der whitelist entfernen.<br>
Weitere Informationen im Freifunk Franken Wiki: https://wiki.freifunk-franken.de/w/Freifunk-BGP-Gateway#Freifunk_Gateway_IPv6_verteilen<br>
<a href="https://www.itstall.de/impressum/">Impressum</a>


</body>
