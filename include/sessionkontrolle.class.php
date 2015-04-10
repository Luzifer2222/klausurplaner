<?php

class sessionkontrolle
{

	function AdminBereich ()
	{
		// Starten/Wiederaufnehmen einer Session
		session_start();
		
		// Prüfen ob das Array der der Session initialisiert wurde
		if (!isset($_SESSION['benutzername']) && !isset($_SESSION['administrator']))
		{
			// die Session nicht initialisiert wurde.
			exit("<p>Sie haben keinen Zugang zu der Seite!<br><a href=\"/index.php\">Hauptseite</a></p>");
		
		}
		
		if ($_SESSION['administrator'] != true)
		{
			
			// Programm abbruch, da der Benutzer kein Administrator ist
			exit("<p>Sie haben keinen Zugang zu der Seite!<br><a href=\"/index.php\">Hauptseite</a></p>");
		}
	
	}

	function UserBereich ()
	{
		// Starten/Wiederaufnehmen einer Session
		session_start();
		
		// Prüfen ob das Array der der Session initialisiert wurde
		if (!isset($_SESSION['benutzername']))
		{
			// Programm abbruch, da die Session nicht initialisiert wurde.
			exit(
						"<p>Benutzername oder Passwort falsch!</p>
          <p>Sie haben keinen Zugang zu der Seite! <br>
		  <a href=\"/index.php\">Hauptseite</a>");
		}
	}
	
	function navigation (){
					
			// Prüfen ob das Array der der Session initialisiert wurde
			if (isset($_SESSION['benutzername']))
			{
				 return $sessionErfolgreich = 1;
			}
			
			return $sessionErfolgreich = 0;
		
			
	}

	function ausloggen ()
	{
		
		// Beenden einer Session
		session_destroy();
		
		// Vorbesetzen des Session Arrays
		// Damit alle daten aus dem $_SESSION
		// Array gelöscht sind (Sicherheit!)
		$_SESSION = array();
	}

}

?>