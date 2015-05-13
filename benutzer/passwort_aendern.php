<?php

// Kontrolle ob User angemeldet ist und Administratorrechte hat
$pruefeSession = new sessionkontrolle();
$pruefeSession->UserBereich();

?>
   
<?php

// Datenbankverbindung initialisieren
$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);

// Datenbank Colloation auf UTF-8 stellen
$datenbank->set_charset('utf8');

// Überprüfung ob der Submitbutton gedrückt wurde
// Zuständig für das Ändern des Passwortes
// in der Lehrer Tabelle
if (isset($_POST['aendern']))
{
	// Überprüfung ob alle Felder des Einfügeformulars ausgefüllt wurden
	if ($_POST['pwd'] != "")
	{
		if ($_POST['pwd'] == $_POST['wiederholen'])
		{
			// Erstellen der Einfügeanweisung in SQL
			$insertquery = "UPDATE lehrer ";
			$insertquery .= "SET passwort = '";
			$insertquery .= verschluesselLogin($_POST['pwd']);
			$insertquery .= "' WHERE lehrerID = '" . $_SESSION['ID'] . "'";
			
			// Einfügen der Formulardaten in die Lehrertabelle
			$datenbank->query($insertquery);
			
			// Überprüfung ob der Datensatz angelegt wurde
			if ($datenbank->affected_rows > 0)
			{
				// Speichern des Ausgabestrings in eine Variable
				$ausgabe = "<hr><p class=\"erfolgreich\">Das Passwort wurde geändert.</p>";
			}
			else
			{
				// Speichern des Fehlerstrings in eine Variable
				$ausgabe = "<hr><p class=\"error\">Das alte und das neue Passwort stimmen überein!</p>";
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
?>

<form class="anlegen" action="<?php $_SERVER['PHP_SELF']?>" method="post" name="passwortaendern" class="passwortaendern">
	<fieldset>
		<legend>Passwort ändern</legend>
		<p>
			<label for="benutzername">Benutzername:</label>
							<?php
							echo $_SESSION['benutzername'];
							?>
		</p>
		<p>
			<label for="passwort">Passwort:</label><input type="password" min="5" name="pwd">
		</p>
		<p>
			<label for="wiederholen">Wiederholen:</label><input type="password" min="5" name="wiederholen">
		</p>
		<p class="button">
			<label>&nbsp;</label><input type="submit" name="aendern" value="Passwort ändern"> <input type="reset" name="reset" value="Zurücksetzen">
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

<?php
// Schließen der Datenbank am Ende der Seite
$datenbank->close();
?>