<?php
// Titel:           lehrer_anlegen.php
// Version:         1.0
// Autor:			PHPmeetsSQL
// Datum:           20.05.15
// Beschreibung:    Zuständig für das Anlegen/Bearbeiten/Löschen der Benutzer/Lehrer

// Kontrolle ob User angemeldet ist und Administratorrechte hat
$pruefeSession = new sessionkontrolle();
$pruefeSession->AdminBereich();

?>
 
<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

// Ändern eines oder mehrerer Lehrer
// in der Lehrer Tabelle
if (isset($_POST['aendernlehrer']) && isset($_POST['checkaendern']))
{
	$ausgabe = "<hr>";
	
	// Überprüfung ob das Administratorrecht verändert wurde
	// Zuständig für das Ändern des Administratorrechtes
	// in der Lehrer Tabelle

	// Einfache Abfrage
	$abfrageadmin = "SELECT administrator ";
	$abfrageadmin .= "FROM lehrer ";
	$abfrageadmin .= "WHERE lehrerID = '" . $_POST['checkaendern'] . "';";
	
	// Ergebnis der Abfrage aus $abfrageadmin
	$ergabfrageadmin = $datenbank->query($abfrageadmin);
	
	while ($daten = $ergabfrageadmin->fetch_object())
	{
		$istadmin = $daten->administrator;
	}
	
	$administratorneu = 0;
	$admin = array();
	if (isset($_POST['adminneu']))
	{
		$admin = $_POST['adminneu'];
		foreach ($admin as $a)
		{
			if ($a == $_POST['checkaendern'])
			{
				$administratorneu = 1;
			}
		}
	}
	else
	{
		$administratorneu = 0;
	}
	
	if ($istadmin != $administratorneu)
	{
		// Erstellen der Einfügeanweisung in SQL
		$insertquery = "UPDATE lehrer ";
		$insertquery .= "SET administrator = '" . $administratorneu . "' ";
		$insertquery .= "WHERE lehrerID = '" . $_POST['checkaendern'] . "';";
		
		// Einfügen der Formulardaten in die Lehrertabelle
		$datenbank->query($insertquery);
			
		// Überprüfung ob der Datensatz angelegt wurde
		if ($datenbank->affected_rows > 0)
		{
			// Speichern des Ausgabestrings in eine Variable
			$ausgabe .= "<p class=\"erfolgreich\">Das Administrationsrecht wurde geändert.</p>";
		}
		else
		{
			// Speichern des Fehlerstrings in eine Variable
			$ausgabe .= "<p class=\"error\">Das Administrationsrecht konnte nicht geändert werden!</p>";
		}
	}
	
	// Überprüfung ob ein neues Passwort eingegeben wurde
	// Zuständig für das Ändern des Passwortes
	// in der Lehrer Tabelle

	
	if ($_POST['neupwd' . $_POST['checkaendern']] != "" && $_POST['neupwd' . $_POST['checkaendern']] != NULL)
	{
		if ($_POST['neupwd'] == $_POST['neuwiederholen'])
		{
			// Erstellen der Einfügeanweisung in SQL
			$insertquery = "UPDATE lehrer ";
			$insertquery .= "SET passwort = '";
			$insertquery .= verschluesselLogin(mysqli_real_escape_string($datenbank,(utf8_decode($_POST['neupwd']))));
			$insertquery .= "' WHERE lehrerID = '" . $_POST['checkaendern'] . "';";
			
			// Einfügen der Formulardaten in die Lehrertabelle
			$datenbank->query($insertquery);
			
			// Überprüfung ob der Datensatz angelegt wurde
			if ($datenbank->affected_rows > 0)
			{
				// Speichern des Ausgabestrings in eine Variable
				$ausgabe .= "<p class=\"erfolgreich\">Das Passwort wurde geändert.</p>";
			}
			else
			{
				// Speichern des Fehlerstrings in eine Variable
				$ausgabe .= "<p class=\"error\">Das Passwort konnte nicht geändert werden!</p>";
			}
		}
		else
		{
			// Speichern des Fehlerstrings in eine Variable
			$ausgabe .= "<p class=\"error\">Das Passwort stimmt nicht überein!</p>";
		}
	}
	
	// Überprüfung ob ein neuer Nachname eingegeben wurde
	// Zuständig für das Ändern des Nachnamens
	// in der Tabelle Lehrer
	if ($_POST['neuernachname' . $_POST['checkaendern']] != "")
	{
		// Einfache Abfragen
		$fragelehrer = "select nachname ";
		$fragelehrer .= "from lehrer ";
		$fragelehrer .= "where lehrerID = '" . $_POST['checkaendern'] . "';";

		// Ergebnis der Abfrage aus $fragelehrer
		$ergfragelehrer = $datenbank->query($fragelehrer);
		
		$pruefe = NULL;
		while ($daten = $ergfragelehrer->fetch_object())
		{
			$pruefe = $daten->nachname;
		}
		
		if ($_POST['neuernachname' . $_POST['checkaendern']] != $pruefe)
		{
			// Erstellen der Einfügeanweisung in SQL
			$insertquery = "UPDATE lehrer ";
			$insertquery .= "SET nachname = '" . mysqli_real_escape_string($datenbank,($_POST['neuernachname' . $_POST['checkaendern']])) . "'";
			$insertquery .= " WHERE lehrerID = '" . $_POST['checkaendern'] . "'";
			
			// Einfügen der Formulardaten in die Lehrertabelle
			$datenbank->query($insertquery);
			
			// Überprüfung ob der Datensatz angelegt wurde
			if ($datenbank->affected_rows > 0)
			{
				// Speichern des Ausgabestrings in eine Variable
				$ausgabe .= "<p class=\"erfolgreich\">Der Nachname wurde geändert.</p>";
			}
			else
			{
				// Speichern des Fehlerstrings in eine Variable
				$ausgabe .= "<p class=\"error\">Der Nachname konnte nicht geändert werden!</p>";
			}
		}
	}
	
	// Überprüfung ob ein neuer Benutzername eingegeben wurde
	// Zuständig für das Ändern des Benutzernamens
	// in der Tabelle Lehrer
	if ($_POST['neuerbenutzername' . $_POST['checkaendern']] != "")
	{
		// Einfache Abfragen
		$fragelehrer = "select benutzername ";
		$fragelehrer .= "from lehrer ";
		$fragelehrer .= "where lehrerID = '" . $_POST['checkaendern'] . "';";

		// Ergebnis der Abfrage aus $fragelehrer
		$ergfragelehrer = $datenbank->query($fragelehrer);
		
		$pruefe = NULL;
		while ($daten = $ergfragelehrer->fetch_object())
		{
			$pruefe = $daten->benutzername;
		}
		
		if ($_POST['neuerbenutzername' . $_POST['checkaendern']] != $pruefe)
		{
			
			// Erstellen der Einfügeanweisung in SQL
			$insertquery = "UPDATE lehrer ";
			$insertquery .= "SET benutzername = '" . mysqli_real_escape_string($datenbank,($_POST['neuerbenutzername' . $_POST['checkaendern']])) . "'";
			$insertquery .= " WHERE lehrerID = '" . $_POST['checkaendern'] . "'";
			
			// Einfügen der Formulardaten in die Lehrertabelle
			$datenbank->query($insertquery);
			
			// Überprüfung ob der Datensatz angelegt wurde
			if ($datenbank->affected_rows > 0)
			{
				// Speichern des Ausgabestrings in eine Variable
				$ausgabe .= "<p class=\"erfolgreich\">Der Benutzername wurde geändert.</p>";
			}
			else
			{
				// Speichern des Fehlerstrings in eine Variable
				$ausgabe .= "<p class=\"error\">Der Benutzername konnte nicht geändert werden!</p>";
			}
		}
	}
	
	// Überprüfung ob die Abteilung geändert wurde
	// Zuständig für das Ändern der Abteilung
	// in der Tabelle Lehrer
	if ($_POST['neueabteilung' . $_POST['checkaendern']] != "")
	{
		// Einfache Abfragen
		$fragelehrer = "select a.abteilungID ";
		$fragelehrer .= "from lehrer l, abteilung a ";
		$fragelehrer .= "where l.lehrerID = '" . $_POST['checkaendern'] . "' AND a.abteilungID = l.abteilungID;";
		
		// Ergebnis der Abfrage aus $fragelehrer
		$ergfragelehrer = $datenbank->query($fragelehrer);
		
		$pruefe = NULL;
		while ($daten = $ergfragelehrer->fetch_object())
		{
			$pruefe = $daten->abteilungID;
		}
		if ($_POST['neueabteilung' . $_POST['checkaendern']] != $pruefe)
		{
			echo "test";
			// Erstellen der Einfügeanweisung in SQL
			$insertquery = "UPDATE lehrer ";
			$insertquery .= "SET abteilungID = '" . $_POST['neueabteilung' . $_POST['checkaendern']] . "'";
			$insertquery .= " WHERE lehrerID = '" . $_POST['checkaendern'] . "'";
			
			// Einfügen der Formulardaten in die Lehrertabelle
			$datenbank->query($insertquery);
			
			// Überprüfung ob der Datensatz angelegt wurde
			if ($datenbank->affected_rows > 0)
			{
				// Speichern des Ausgabestrings in eine Variable
				$ausgabe .= "<p class=\"erfolgreich\">Die Abteilung wurde geändert.</p>";
			}
			else
			{
				// Speichern des Fehlerstrings in eine Variable
				$ausgabe .= "<p class=\"error\">Die Abteilung konnte nicht geändert werden!</p>";
			}
		}
	}
	if ($ausgabe == "<hr>")
	{
		/// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Es muss mindestens ein Feld verändert werden!</p>";
	}
}

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Einfügen eines neuen Lehrers
// in die Lehrer Tabelle
if (isset($_POST['neuanlegen']))
{
	// Überprüfung ob alle Felder des Einfügeformulars ausgefüllt wurden
	if ($_POST['vname'] != "" && $_POST['nname'] != "" && $_POST['kuerzel'] != "" && $_POST['bname'] != "" && $_POST['pwd'] != "")
	{
		if ($_POST['pwd'] == $_POST['wiederholen'])
		{
			if (!isset($_POST['administrator'][0]))
			{
				$_POST['administrator'][0] = 0;
			}
			// Erstellen der Einfügeanweisung in SQL
			$insertquery = "insert into lehrer ";
			$insertquery .= "(vorname, nachname, kuerzel, benutzername, passwort, administrator, abteilungID) values";
			$insertquery .= "('" . mysqli_real_escape_string($datenbank,$_POST['vname']) . "', '" . mysqli_real_escape_string($datenbank,($_POST['nname'])) . "', '" . mysqli_real_escape_string($datenbank,(strtoupper($_POST['kuerzel']))) . "', '" .
						 mysqli_real_escape_string($datenbank,(strtolower($_POST['bname']))) . "', '";
			$insertquery .= verschluesselLogin(mysqli_real_escape_string($datenbank,(utf8_decode($_POST['pwd'])))) . "', '" . $_POST['administrator'][0] . "', '" . $_POST['abteilung'] . "');";
			
			// Einfügen der Formulardaten in die Lehrertabelle
			$datenbank->query($insertquery);
			
			// Überprüfung ob der Datensatz angelegt wurde
			if ($datenbank->affected_rows > 0)
			{
				// Speichern des Ausgabestrings in eine Variable
				$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde ein neuer Lehrer angelegt.</p>";
			}
			else
			{
				// Speichern des Fehlerstrings in eine Variable
				$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde kein neuer Lehrer angelegt.</p>";
			}
		}
		else
		{
			// Speichern des Fehlerstrings in eine Variable
			$ausgabe = "<hr><p class=\"error\">Das Passwort stimmt nicht überein!</p>";
		}
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Alle Felder müssen ausgefüllt werden!</p>";
	}
}

// Überprüfung ob der Button 'Lösche' gedrückt wurde
if (isset($_POST['loeschelehrer']) && isset($_POST['loesche']))
{
	// Speichern der delete Abfrage und Durchführung der Abfrage
	$abfloeschelehrer = "delete from lehrer where lehrerID = " . $_POST['loesche'] . ";";
	$datenbank->query($abfloeschelehrer);
	
	// Überprüfung ob der Datensatz gelöscht wurde
	if ($datenbank->affected_rows > 0)
	{
		// Speichern des Ausgabestrings in eine Variable
		$ausgabe = "<hr><p class=\"erfolgreich\">Es wurde der Datensatz mit der ID: " . $_POST['loesche'] . " gelöscht.</p>";
	}
	else
	{
		// Speichern des Fehlerstrings in eine Variable
		$ausgabe = "<hr><p class=\"error\">Fehler! Es wurde kein Datensatz gelöscht</p>";
	}
}

// Einfache Abfragen für das Extrahieren der Abteilungen und der Lehrer
$abfrageabteilung = "select * from abteilung;";
$abfrageBenutzer = "select * from lehrer;";
$abfragelehrer = "select l.lehrerID, l.vorname, l.nachname, l.kuerzel, l.benutzername, l.administrator, l.abteilungID, a.name ";
$abfragelehrer .= "from lehrer l, abteilung a ";
$abfragelehrer .= "where l.abteilungID = a.abteilungID";

// Ergebnis der Abfrage aus $abfragelehrer
$ergabfragelehrer = $datenbank->query($abfragelehrer);
?>

<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post" name="lehrereinfuegen" class="lehrereinfuegen">
	<fieldset>
		<legend>Einfügen des Lehrpersonals in das 'CTS'</legend>
		<p>
			<label for="vorname">Vorname:</label> <input type="text" pattern="[A-z0-9ÄÖÜäöü -]{2,50}" min="2" maxlength="50" name="vname" id="vorname">
		</p>
		<p>
			<label for="nachname">Nachname:</label> <input type="text" pattern="[A-z0-9ÄÖÜäöü -]{2,50}" min="2" maxlength="50" name="nname" id="nachname">
		</p>
		<p>
			<label for="kuerzel">Kürzel:</label> <input type="text" pattern="[A-z0-9]{4,5}" min="4" maxlength="5" name="kuerzel" id="kuerzel">
		</p>
		<p>
			<label for="abteilung">Fachbereich:</label> <select name="abteilung" id="abteilung">
							<?php
							
							$ergabteilungdb = $datenbank->query($abfrageabteilung);
							while ($daten = $ergabteilungdb->fetch_object())
							{
								echo "<option value=$daten->abteilungID>" . $daten->name . "</option>";
							}
							
							?>
        </select>
		</p>
		<p>
			<label for="administrator">Administrator:</label> <input type="checkbox" name="administrator[]" value="1">
		</p>
		<p>
			<label for="benutzername">Benutzername:</label> <input type="text" pattern="[A-z0-9]*" min="4" maxlength="15" name="bname">
		</p>
		<p>
			<label for="passwort">Passwort:</label> <input type="password" min="5" name="pwd">
		</p>
		<p>
			<label for="wiederholen">Wiederholen:</label> <input type="password" min="5" name="wiederholen">
		</p>
		<p class="button">
			<label>&nbsp;</label><input type="submit" name="neuanlegen" value="Lehrer anlegen"> <input type="reset" name="reset" value="Zurücksetzen">
		</p>
	</fieldset>
</form>

<?php
// Ausgabe ob Eintrag in die Datenbank erfolgreich war.
if (isset($ausgabe))
{
	echo $ausgabe;
}
?>

<hr>
<form action="" method="post" class="aendern">
	<table class="ausgabe">
		<caption>Angelegte Lehrer:</caption>
		<tr>
			<th>LehrerID</th>
			<th>Vorname</th>
			<th>Nachname</th>
			<th>Kürzel</th>
			<th>Benutzername</th>
			<th>Neues Passwort</th>
			<th>Passwort wiederholen</th>
			<th>Fachbereich</th>
			<th>Admin</th>
			<th><input type="submit" name="loeschelehrer" value="Löschen"></th>
			<th><input type="submit" name="aendernlehrer" value="Ändern"></th>
		</tr>
    <?php
				while ($lehrertabelle = $ergabfragelehrer->fetch_object())
				{
					echo "<tr>";
					echo "<td>" . $lehrertabelle->lehrerID . "</td>";
					echo "<td>" . $lehrertabelle->vorname . "</td>";
					echo "<td><input type=\"text\" class=\"aendern\" name=\"neuernachname" . $lehrertabelle->lehrerID . "\" value=\"" . $lehrertabelle->nachname . "\"></td>";
					echo "<td>" . $lehrertabelle->kuerzel . "</td>";
					echo "<td><input type=\"text\" class=\"aendern\" name=\"neuerbenutzername" . $lehrertabelle->lehrerID . "\" value=\"" . $lehrertabelle->benutzername . "\"></td>";
					echo "<td><input type=\"password\" class=\"aendern\" min=\"5\" name=\"neupwd" . $lehrertabelle->lehrerID . "\"></td>";
					echo "<td><input type=\"password\" class=\"aendern\" min=\"5\" name=\"neuwiederholen" . $lehrertabelle->lehrerID . "\"></td>";
					echo "<td><select class=\"aendern\" name=\"neueabteilung" . $lehrertabelle->lehrerID . "\">";
					$ergabteilungdb = $datenbank->query($abfrageabteilung);
					while ($daten = $ergabteilungdb->fetch_object())
					{
						echo "<option value=\"$daten->abteilungID\" ";
						if ($daten->abteilungID == $lehrertabelle->abteilungID)
						{
							echo "selected=\"selected\"";
						}
						echo " >" . $daten->name . "</option>";
					}
					echo "</select></td>";
					if ($lehrertabelle->administrator == 1)
					{
						echo "<td><input type=\"checkbox\" checked=\"checked\" name=\"adminneu[]\" value=\"" . $lehrertabelle->lehrerID . "\"></td>";
					}
					else
					{
						echo "<td><input type=\"checkbox\" name=\"adminneu[]\" value=\"" . $lehrertabelle->lehrerID . "\"></td>";
					}
					echo "<td><input type=\"radio\" name=\"loesche\" value=\"" . $lehrertabelle->lehrerID . "\"></td>";
					echo "<td><input type=\"radio\" name=\"checkaendern\" value=\"" . $lehrertabelle->lehrerID . "\"></td>";
					echo "</tr>";
				}
				?>
    <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<th><input type="submit" name="loeschelehrer" value="Löschen"></th>
			<th><input type="submit" name="aendernlehrer" value="Ändern"></th>
		</tr>
	</table>
</form>

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>