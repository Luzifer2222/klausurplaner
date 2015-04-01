<?php
//Starten einer neuen Session
session_start();

//Beenden einer Session
session_destroy();

//Vorbesetzen des Session Arrays
//Damit alle daten aus dem $_SESSION
//Array gelöscht sind (Sicherheit!)
$_SESSION = array();
?>

<!doctype html>
<html>
  <head>
    <!-- Angaben zum Zeichensatz, immer UTF-8 -->
    <meta charset="utf-8"> <!-- Angabe des Zeichensatzes für HTML5 -->
    <meta http-eqiv="content-type" content="text/html; charset=utf-8"> <!-- Angabe des Zeichensatzes für ältere Browser -->
    
    <!-- Angabe wie die Seite sich verhalten soll -->
    <meta http-equiv="expires" content="0"> <!-- Seite wird immer von Server geholt und nicht aus dem cache geladen -->
        
    <!-- Angabe zu den Autoren der Website und andere Informationen -->
    <meta name="author" content="Daniel Thielking, Robin Gebhardt, Pascal Lawitzky">
    <meta name="project-group" content="PHPmeetsSQL G3">
    <meta name="description" content="Klausurplaner Software">
    
    <!-- Einfügen des FavoritenIcons -->
    <link rel="icon" href="../pictures/favicon/favicon.png" sizes="16x16 32x32" type="images/png">
    <!-- Einfügen der CSS-Dateine -->
	<link rel="stylesheet" href="./style/style.css" type="text/css">
    
    <title>Login-Page</title>
  </head>
  <body>
    <form action="index.php" method="post" name="anmeldeformular">
    <fieldset>
      <legend>Anmeldeformular</legend>
      <p><label for="benutzername">Benutzername<input type="text" name="benutzername"></label></p>
      <p><label for="passwort">Passwort<input type="password" name="passwort"></label></p>
      <input type="submit">
      </fieldset>
    </form>
  </body>
</html>