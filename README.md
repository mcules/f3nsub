# f3nsub

apt-get install composer php-bcmath

git clone [...]

composer require s1lentium/iptools


Beispiel: http://fff-jupiter.fff.community/f3nsub

zum eintragen von Subnetzen so vorgehen:

config.php $initaktiv auf 1 setzen

.../init.php?subnet=fdff::/40&site=48 somit werden alle /48 die in dem /40 enthalten sind in der Datenbank eingetragen

anschließend $initaktiv in der config.php wieder auf 0 setzen 
