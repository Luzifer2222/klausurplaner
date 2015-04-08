<?php

function getFeiertage ($jahr)
{
	// Berechnung der Variablen Feiertage anhand der easter_date Funktion
	$osterSonntag = date("d.m.Y", easter_date($jahr));
	$osterMontag = date("d.m.Y", strtotime("$osterSonntag +1 day"));
	$karFreitag = date("d.m.Y", strtotime("$osterSonntag -2 day"));
	$ascherMittwoch = date("d.m.Y", strtotime("$osterSonntag -46 day"));
	$veilchenDienstag = date("d.m.Y", strtotime("$ascherMittwoch -1 day"));
	$rosenMontag = date("d.m.Y", strtotime("$ascherMittwoch -2 day"));
	$weiberFastnacht = date("d.m.Y", strtotime("$ascherMittwoch -6 day"));
	$christiHimmelfahrt = date("d.m.Y", strtotime("$osterSonntag +40 day"));
	$pfingstMontag = date("d.m.Y", strtotime("$osterSonntag +50 day"));
	$fronleichnahm = date("d.m.Y", strtotime("$osterSonntag +60 day"));
	
	// Feste Feiertage Nordrhein-Westfalen
	$neujahr = date("d.m.Y", mktime(0, 0, 0, 1, 1, $jahr));
	$tagDerArbeit = date("d.m.Y", mktime(0, 0, 0, 5, 1, $jahr));
	$tagDerDeutschenArbeit = date("d.m.Y", mktime(0, 0, 0, 10, 3, $jahr));
	$allerheiligen = date("d.m.Y", mktime(0, 0, 0, 11, 1, $jahr));
	$ersterWeihnachtsfeiertag = date("d.m.Y", mktime(0, 0, 0, 12, 25, $jahr));
	$zweiterWeihnachtsfeiertag = date("d.m.Y", mktime(0, 0, 0, 12, 26, $jahr));
	
	// Rückgabe aller Feiertage mit dazugehörigem Namen
	// Zur Ausgabe muss eine foreach ( $name as $key => $value ) verwendet werden
	return $ausgabeArray = array(
		
			"Neujahr" => $neujahr, 
			"Weiberfastnacht" => $weiberFastnacht, 
			"Rosenmontag" => $rosenMontag, 
			"Veilchendienstag" => $veilchenDienstag, 
			"Aschermittwoch" => $ascherMittwoch, 
			"Karfreitag" => $karFreitag, 
			"Ostersonntag" => $osterSonntag, 
			"Ostermontag" => $osterMontag, 
			"Tag der Arbeit" => $tagDerArbeit, 
			"Christi Himmelfahrt" => $christiHimmelfahrt, 
			"Pfingstmontag" => $pfingstMontag, 
			"Fronleichnam" => $fronleichnahm, 
			"Tag der Deutschen Einheit" => $tagDerDeutschenArbeit, 
			"Allerheiligen" => $allerheiligen, 
			"Erster Weihnachtstag" => $ersterWeihnachtsfeiertag, 
			"Zweiter Weihnachtstag" => $zweiterWeihnachtsfeiertag
	);

}

?>