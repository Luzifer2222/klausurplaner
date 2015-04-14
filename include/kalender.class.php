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

	function baueKalender ($jahr, $monat)
	{
		$internesJahr = $jahr;
		$internerMonat = $monat;
		
		/*
		 * Generierung des Zeitstempels für den ersten Wochentag im angegebenen Monat
		 */
		$generiereZeitstempelDesErstenWochentagsImMonat = mktime(1, 0, 0, $internerMonat, 1, $internesJahr, 0);
		
		/*
		 * Angabe über den ersten Wochentag in Numerischerform
		 */
		$ersterWochentagImMonat = date("w", $generiereZeitstempelDesErstenWochentagsImMonat);
		
		/*
		 * Gibt einen Wert zwischen 28 und 31 zurück, jenachdem wie lang der Monat ist
		 */
		$anzahlTageDesMonats = date("t", mktime(1, 1, 1, $internerMonat));
		
		/*
		 * Boolscherwert der auf true gesetzt wird, sobald der erste Tag in den Kalender geschrieben
		 * wurde
		 */
		$wurdeErsterWochentagGeschrieben = false;
		
		/*
		 * Wurde mit der Nummerierung im Kalender schon begonnen wird diese Variable auf True
		 * gesetzt
		 */
		$beginneMitNummerierung = false;
		
		/*
		 * Zähl Variable um eine Wochtagnummerierung im generierten Kalender einzufügen
		 */
		$tagDatum = 1;
		
		$wochentagNamenArray = array(
			
				// Erstellung der Tabelle für die
				// Monatsansicht
				"Montag", 
				"Dienstag", 
				"Mittwoch", 
				"Donnerstag", 
				"Freitag", 
				"Samstag", 
				"Sonntag"
		);
		
		// Damit der Sonntag als 7ter Tag der Woche gilt und nicht der 0te
		if ($ersterWochentagImMonat == 0)
		{
			$ersterWochentagImMonat += 7;
		}
		
		// Beginn der Kalender Tabelle
		echo "<table class=\"kalender\">";
		
		/*
		 * Schleife für die Zeilenanzahl Anzahl der Zeilen sind 7
		 */
		for ($zeilen = 1 ; $zeilen <= 7 ; $zeilen++)
		{
			echo "<tr>";
			
			/*
			 * Schleife für die Spaltenanzahl Da eine Woche sieben Tage hat hat die Tabelle auch
			 * sieben Spalten, wobei die erste Spalte mit Montag beginnt
			 */
			for ($spalten = 1 ; $spalten <= 7 ; $spalten++)
			{
				
				/*
				 * Prüft ob gerade die erste Zeile der Tabelle geschrieben wird, wenn ja wird der
				 * Wochentagname geschrieben
				 */
				if ($zeilen == 1)
				{
					echo "<th>{$wochentagNamenArray[$spalten-1]}</th>";
				}
				else
				{
					if ($ersterWochentagImMonat == $spalten && $wurdeErsterWochentagGeschrieben == false)
					{
						echo "<td><p class=\"datum\">$tagDatum</p></td>";
						$tagDatum++;
						$beginneMitNummerierung = true;
						$wurdeErsterWochentagGeschrieben = true;
					}
					elseif ($beginneMitNummerierung == true && $tagDatum <= $anzahlTageDesMonats)
					{
						echo "<td><p class=\"datum\">$tagDatum</p></td>";
						$tagDatum++;
					}
					else
					{
						echo "<td>&nbsp;</td>";
					}
				}
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