<?php

function verschluesselLogin ($passwort)
{
	
	$salt = "PHPmeetsSQL" . $passwort;
	$verschluesseltesPasswort = hash('sha256', $salt);
	return $verschluesseltesPasswort;
}
?>