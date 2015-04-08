<!-- Grundgerüst für HTML-Dokumente bitte immer dieses Verwenden!  -->

<?php
// Starten/Wiederaufnehmen einer Session
session_start();

/* Für normalen Benutzerbereich */
// Prüfen ob das Array der der Session initialisiert wurde
if (!isset($_SESSION['benutzername']))
{
	// Programm abbruch, da die Session nicht initialisiert wurde.
	exit("<p>Sie haben keinen Zugang zu der Seite!<br><a href=\"/login.php\">Login Seite</a>");
}
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
<!-- Seite wird immer von Server geholt und nicht aus dem cache geladen -->

<!-- Angabe zu den Autoren der Website und andere Informationen -->
<meta name="author"
	content="Daniel Thielking, Robin Gebhardt, Pascal Lawitzky">
<meta name="project-group" content="PHPmeetsSQL G3">
<meta name="description" content="Klausurplaner Software">

<!-- Einfügen des FavoritenIcons -->
<link rel="icon" href="../pictures/favicon/favicon.png"
	sizes="16x16 32x32" type="images/png">
<!-- Einfügen der CSS-Dateien -->
<link rel="stylesheet" href="Hier Stylesheet rein" type="text/css">

<title>Class Test Scheduler 'CTS' by Daniel Thielking, Robin Gebhardt,
	Pascal Lawitzky</title>
</head>
<body>
	<!-- sichtbarer Inhalt -->
</body>
</html>