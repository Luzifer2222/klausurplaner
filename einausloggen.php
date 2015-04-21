<?php

$angemeldet = false;

if (isset($_POST['anmelden']))
{
	// Überprüfung ob der Benutzername von der navigation.php kommt
	if (isset($_POST['benutzername']))
	{
		// Öffnen der SQL-Datenbank zur Überprüfung der Anmeldedaten
		$datenbank = new mysqli($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);
		$datenbank->set_charset('utf8');
		
		// Erstellen der Abfrage um das Passwort des
		// Benutzers aus der Datenbank zu holen
		$passwortabfrage = "SELECT lehrerID, passwort, administrator  from lehrer where benutzername like '" . $_POST['benutzername'] . "'";
		$abfrageerg = $datenbank->query($passwortabfrage);
		// Extrahieren der Daten aus der Abfrage
		$queryErgebnis = $abfrageerg->fetch_object();
		
		// Überprüfung ob die Abfrage erfolgreich war
		if ($abfrageerg->num_rows == 1)
		{
			// Passwort Überprüfung
			if ($queryErgebnis->passwort == verschluesselLogin($_POST['passwort']))
			{
				// Setzen des Sessionarray mit dem Benutzernamen der eingegeben wurde
				$_SESSION['benutzername'] = $_POST['benutzername'];
				$_SESSION['administrator'] = $queryErgebnis->administrator;
				$_SESSION['ID'] = $queryErgebnis->lehrerID;
				$angemeldet = true;
			}
		}
	}
	
	// Prüfen ob das Array der der Session initialisiert wurde
	if (!isset($_SESSION['benutzername']))
	{
		// Programm abbruch, da die Session nicht initialisiert wurde.
		$einausgeloggt = "<p class=\"error\">Benutzername oder Passwort falsch!";
	}
	
	$datenbank->close();
}

if (isset($_POST['abmelden']))
{
	$session = new sessionkontrolle();
	
	$session->ausloggen();
	
	$angemeldet = false;
}
?>

			<?php
			if ($angemeldet)
			{
				$einausgeloggt = "<p class=\"erfolgreich\">Sie sind nun eingeloggt!</p>";
			}
			elseif (isset($einausgeloggt))
			{}
			elseif (!$angemeldet)
			{
				$einausgeloggt = "<p class=\"erfolgreich\">Sie sind nun ausgeloggt!</p>";
			}
			?>
			