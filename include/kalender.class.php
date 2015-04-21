<?php

class kalender
{

	private $host;

	private $user;

	private $password;

	private $database;

	function baueKalender ($jahr)
	{
		// Datenbankverbindung initialisieren
		$datenbank = new mysqli($this->host, $this->user, $this->password, $this->database);
		$datenbank->set_charset('utf8');
		
		$kalenderAnfang = date("d.m.Y", mktime(0, 0, 0, 8, 1, $jahr));
		$kalenderEnde = date("d.m.Y", mktime(0, 0, 0, 7, 31, ($jahr + 1)));
		
		$wochentagKalenderAnfang = date("N", strtotime($kalenderAnfang));
		
		while ($wochentagKalenderAnfang > 1)
		{
			$kalenderAnfang = date("d.m.Y", strtotime("$kalenderAnfang -1 day"));
			$wochentagKalenderAnfang--;
		}
		
		echo "<table class=\"kalender\">";
		echo "<tr>
				<th>KW</th>
				<th class=\"vonbis\">Von - Bis</th>
				<th>Montag</th>
				<th>Dienstag</th>
				<th>Mittwoch</th>
				<th>Donnerstag</th>
				<th>Freitag</th>
			  </tr>\n";
		while (strtotime($kalenderAnfang) < strtotime($kalenderEnde))
		{
			echo "<tr>";
			echo "<td>" . date("W", strtotime($kalenderAnfang)) . "</td>\n";
			echo "<td>" . date("d.m.", strtotime("$kalenderAnfang")) . " - " . date("d.m.", strtotime("$kalenderAnfang next friday")) . "</td>\n";
			
			for ($i = 0 ; $i < 5 ; $i++)
			{
				$globaleTermineQuery = "SELECT name, beginndatum, endedatum FROM belegtetage WHERE '" .
							 date("Y-m-d", strtotime("$kalenderAnfang + $i day")) . "' BETWEEN beginndatum AND endedatum;";
				$globaleTermineErgebnis = $datenbank->query($globaleTermineQuery);
				
				if ($globaleTermineErgebnis->num_rows > 0)
				{
					echo "<td class=\"tage\">";
					while ($daten = $globaleTermineErgebnis->fetch_object())
					{
						echo "<p class=\"globalertermin\">" .$daten->name . "</p>";
					}
					echo "</td>";
				}
				else
				{
					echo "<td class=\"tage\"></td>\n";
				}
			}
			
			echo "</tr>\n";
			
			$kalenderAnfang = date("d.m.Y", strtotime("$kalenderAnfang +1 week"));
		
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

	function setDatabaseconnection ($host, $user, $password, $database)
	{
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
	}

}

?>