<?php

class kalender
{

	private $jahr;

	private $monat;

	private $art;

	private $thema;

	private $von;

	private $bis;

	private $fach;

	private $lehrer;

	private $bundesland;

	private $locale;

	function baueKalender ($jahr = NULL, $monat = NULL)
	{
		setlocale(LC_TIME, "de_DE.utf8");
		$kal_datum = mktime(0, 0, 0, $monat, 0, $jahr);
		$kal_tage_gesamt = date("t", $kal_datum);
		$kal_start_timestamp = mktime(0, 0, 0, date("n", $kal_datum), 1, date("Y", $kal_datum));
		$kal_start_tag = date("N", $kal_start_timestamp);
		$kal_ende_tag = date("N", mktime(0, 0, 0, date("n", $kal_datum), $kal_tage_gesamt, date("Y", $kal_datum)));
		
		echo "<table class=\"kalender\">\n";
		echo "<caption>" . strftime("%B %Y", $kal_datum) . "</caption>\n";
		echo "<tr>\n";
		echo "<th>Montag</th>\n";
		echo "<th>Dienstag</th>\n";
		echo "<th>Mittwoch</th>\n";
		echo "<th>Donnerstag</th>\n";
		echo "<th>Freitag</th>\n";
		echo "<th>Samstag</th>\n";
		echo "<th>Sonntag</th>\n";
		echo "</tr>\n";
		
		for ($i = 1 ; $i <= $kal_tage_gesamt + ($kal_start_tag - 1) + (7 - $kal_ende_tag) ; $i++)
		{
			$kal_anzeige_akt_tag = $i - $kal_start_tag;
			$kal_anzeige_heute_timestamp = strtotime($kal_anzeige_akt_tag . " day", $kal_start_timestamp);
			$kal_anzeige_heute_tag = date("j", $kal_anzeige_heute_timestamp);
			if (date("N", $kal_anzeige_heute_timestamp) == 1)
			{
				echo "<tr>\n";
			}
			
			if (date("dmY", $kal_datum) == date("dmY", $kal_anzeige_heute_timestamp))
			{
				echo "<td class=\"heute\">" , $kal_anzeige_heute_tag , "</td>\n";
			}
			elseif ($kal_anzeige_akt_tag >= 0 and $kal_anzeige_akt_tag < $kal_tage_gesamt)
			{
				echo "<td>" , $kal_anzeige_heute_tag , "</td>\n";
			}
			else
			{
				echo "<td class=\"vormonat\">" , $kal_anzeige_heute_tag , "</td>\n";
			}
			if (date("N", $kal_anzeige_heute_timestamp) == 7)
			{
				echo "</tr>\n";
			}
		}
		
		echo "</table>";
	
	}

	function berechneFeiertage ($jahr, $bundesland)
	{
		// Berechnung der Variablen Feiertage anhand der easter_date Funktion
		$osterSonntag = date("d.m.Y", easter_date($jahr));
		$osterMontag = date("d.m.Y", strtotime("$osterSonntag +1 day"));
		$karFreitag = date("d.m.Y", strtotime("$osterSonntag -2 day"));
		$christiHimmelfahrt = date("d.m.Y", strtotime("$osterSonntag +40 day"));
		$pfingstSonntag = date("d.m.Y", strtotime("$osterSonntag +49 day"));
		$pfingstMontag = date("d.m.Y", strtotime("$osterSonntag +50 day"));
		$fronleichnahm = date("d.m.Y", strtotime("$osterSonntag +60 day"));
		
		// Feste Feiertage Nordrhein-Westfalen
		$neujahr = date("d.m.Y", mktime(0, 0, 0, 1, 1, $jahr));
		$heiligDreiKoenige = date("d.m.Y", mktime(0, 0, 0, 1, 6, $jahr));
		$tagDerArbeit = date("d.m.Y", mktime(0, 0, 0, 5, 1, $jahr));
		$mariaeHimmelfahrt = date("d.m.Y", mktime(0, 0, 0, 8, 15, $jahr));
		$tagDerDeutschenArbeit = date("d.m.Y", mktime(0, 0, 0, 10, 3, $jahr));
		$reformationstag = date("d.m.Y", mktime(0, 0, 0, 10, 31, $jahr));
		$allerheiligen = date("d.m.Y", mktime(0, 0, 0, 11, 1, $jahr));
		$ersterWeihnachtsfeiertag = date("d.m.Y", mktime(0, 0, 0, 12, 25, $jahr));
		$zweiterWeihnachtsfeiertag = date("d.m.Y", mktime(0, 0, 0, 12, 26, $jahr));
		
		// Rückgabe aller Feiertage mit dazugehörigem Namen
		// Zur Ausgabe muss eine foreach ( $name as $key => $value ) verwendet werden
		return $this->feiertage = array(
			
				"Neujahr" => $neujahr, 
				"Karfreitag" => $karFreitag, 
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

	function setTermin ($art, $thema, $von, $bis, $fach, $lehrer)
	{
	
	}

	function getTermin ()
	{
	
	}
}

?>