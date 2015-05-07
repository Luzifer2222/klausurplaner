<?php

if (isset($_POST['test']))
{
	echo pruefeDatum($_POST['test']);
}

function pruefeDatum ($datum)
{
	$tag = substr($datum, 0, 2);
	$monat = substr($datum, 3, 2);
	$jahr = substr($datum, 6, 4);
	
	$tag = intval($tag);
	$monat = intval($monat);
	$jahr = intval($jahr);
	
	if (is_int($tag) && is_int($monat) && is_int($jahr))
	{
		if ($tag > 0 && $tag <= 31)
		{
			if ($monat > 0 && $monat <= 12)
			{
				if ($jahr >= 1970 && $jahr <= 2031)
				{	
					if (checkdate($monat, $tag, $jahr))
					{
						return true;
					}
				}
			}
		}
	}
	
	return false;
}

?>

<form method="post" action="">
	<input type="text"  name="test" />
</form>