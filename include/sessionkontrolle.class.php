<?php

class sessionkontrolle
{

	private $sessionErfolgreich = false;

	function AdminBereich ()
	{
		// Starten/Wiederaufnehmen einer Session
		session_start();
		
		// Prüfen ob das Array der der Session initialisiert wurde
		if (!isset($_SESSION['benutzername']) && !isset($_SESSION['administrator']))
		{
			// die Session nicht initialisiert wurde.
			exit("<p>Sie haben keinen Zugang zu der Seite!<br><a href=\"/login.php\">Login Seite</a></p>");
		
		}
		
		if ($_SESSION['administrator'] != true)
		{
			
			// Programm abbruch, da der Benutzer kein Administrator ist
			exit("<p>Sie haben keinen Zugang zu der Seite!<br><a href=\"/login.php\">Login Seite</a></p>");
		}
		
		$this->sessionErfolgreich = true;
	
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
		  <a href=login.php>Login Seite</a>");
		}
		
		$this->sessionErfolgreich = true;
	}
	
	function sessionErfolgreich() {
		return $this->sessionErfolgreich;
	}
}

?>