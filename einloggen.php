<?php
// Einbinden der benötigten Funktionen
include './config/cts.conf.php';
include './include/loginfunktion.php';

// Starten/Wiederaufnehmen einer Session
session_start();

// Überprüfung ob der Aufruf von login.php kommt
if (isset($_POST['benutzername']))
{
	// Öffnen der SQL-Datenbank zur Überprüfung der Anmeldedaten
	$link = mysqli_connect($database_conf['host'], $database_conf['user'], $database_conf['password'], $database_conf['database']);
	
	// Erstellen der Abfrage um das Passwort des
	// Benutzers aus der Datenbank zu holen
	$passwortabfrage = "SELECT passwort, administrator from lehrer where benutzername like '" . $_POST['benutzername'] . "'";
	$abfrageerg = mysqli_query($link, $passwortabfrage);
	// Extrahieren der Daten aus der Abfrage
	$queryErgebnis = mysqli_fetch_object($abfrageerg);
	
	// Überprüfung ob die Abfrage erfolgreich war
	if (mysqli_num_rows($abfrageerg) == 1)
	{
		// Passwort Überprüfung
		if ($queryErgebnis->passwort == verschluesselLogin($_POST['passwort']))
		{
			// Setzen des Sessionarray mit dem Benutzernamen der eingegeben wurde
			$_SESSION['benutzername'] = $_POST['benutzername'];
			$_SESSION['administrator'] = $queryErgebnis->administrator;
		}
	}
}

// Prüfen ob das Array der der Session initialisiert wurde
if (!isset($_SESSION['benutzername']))
{
	// Programm abbruch, da die Session nicht initialisiert wurde.
	exit("<p>Benutzername oder Passwort falsch!</p>
            <p>Sie haben keinen Zugang zu der Seite! <br><a href=login.php>Login Seite</a>");
}

mysqli_close($link);

?>
<!doctype html>
<html>
<head>
<!-- Angaben zum Zeichensatz, immer UTF-8 -->
<meta charset="utf-8">
<!-- Angabe des Zeichensatzes für HTML5 -->
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<!-- Angabe des Zeichensatzes für ältere Browser -->

<!-- Angabe wie die Seite sich verhalten soll -->
<meta http-equiv="expires" content="0">
<meta http-equiv="refresh" content="3; URL=/index.php">
<!-- Seite wird immer von Server geholt und nicht aus dem cache geladen -->

<!-- Angabe zu den Autoren der Website und andere Informationen -->
<meta name="author"
	content="Daniel Thielking, Robin Gebhardt, Pascal Lawitzky">
<meta name="project-group" content="PHPmeetsSQL G3">
<meta name="description" content="Klausurplaner Software">

<!-- Einfügen des FavoritenIcons -->
<link rel="icon" href="../pictures/favicon/favicon.png"
	sizes="16x16 32x32" type="images/png">
<!-- Einfügen der CSS-Dateine -->
<link rel="stylesheet" href="Hier Stylesheet rein" type="text/css">

<title>Class Test Scheduler 'CTS' by Daniel Thielking, Robin Gebhardt,
	Pascal Lawitzky</title>
</head>
<body>
	<p>Sie sind eingeloggt!</p>
	<p>Sie werden auf die Hauptseite in 3 Sekunden weitergeleitet</p>
	<p>
		Sollten Sie nicht weitergeleitet werden, bitte Klicken Sie <a
			href="/index.php">hier</a>
	</p>
</body>
</html>