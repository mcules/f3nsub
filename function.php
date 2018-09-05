<?php
function getrout($ip)
{
	$file="/tmp/babeldump";
	if(shell_exec('echo "dump" | nc ::1 33123 -q 0 | grep '.$ip.''))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function zahlinwort($zahl)
{
	if ($zahl==0) { return "null"; }
        if ($zahl==1) { return "eins"; }
        if ($zahl==2) { return "zwei"; }
        if ($zahl==3) { return "drei"; }
        if ($zahl==4) { return "vier"; }
        if ($zahl==5) { return "fünf"; }
        if ($zahl==6) { return "sechs"; }
        if ($zahl==7) { return "sieben"; }
        if ($zahl==8) { return "acht"; }
        if ($zahl==9) { return "neun"; }
}
function captcha()
{
	$zahl1=rand(0,9);
	$zahl2=rand(0,9);
	$zeichen=rand(1,2);
	if ($zeichen==1) { 
		$zeichen1="+"; 
		$_SESSION["erg"]=$zahl1+$zahl2;
	}
        if ($zeichen==2) { 
		$zeichen1="*"; 
                $_SESSION["erg"]=$zahl1*$zahl2;
	}
	return ''.zahlinwort($zahl1).' '.$zeichen1.' '.zahlinwort($zahl2).' ergibt:';
}
