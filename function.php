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
		
