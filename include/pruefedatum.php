<?php

function pruefedatum ($datum)
{
	$tag = intval(substr($datum, 0, 2));
	$monat = intval(substr($datum, 3, 2));
	$jahr = intval(substr($datum, 6, 4));
	
	if (checkdate($monat, $tag, $jahr))
	{
		return true;
	}
	return false;
}

?>