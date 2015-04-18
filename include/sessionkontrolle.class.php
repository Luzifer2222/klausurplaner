<?php

class sessionkontrolle
{

	function AdminBereich ()
	{
		// Prüfen ob das Array der der Session initialisiert wurde
		if (!isset($_SESSION['benutzername']) && !isset($_SESSION['administrator']))
		{
			// die Session nicht initialisiert wurde.
			exit("<p class=\"error\">Sie haben keinen Zugang zu der Seite!<br>Sie sind nicht angemeldet.</p>");
		
		}
		
		if ($_SESSION['administrator'] != true)
		{
			
			// Programm abbruch, da der Benutzer kein Administrator ist
			exit("<p class=\"error\">Sie haben keinen Zugang zu der Seite!<br /> Sie sind kein Administrator</p>");
		}
	
	}

	function UserBereich ()
	{
		// Prüfen ob das Array der der Session initialisiert wurde
		if (!isset($_SESSION['benutzername']))
		{
			// Programm abbruch, da die Session nicht initialisiert wurde.
			exit("<p class=\"error\">Sie haben keinen Zugang zu der Seite!<br>Sie sind nicht angemeldet.</p>");
		}
	}

	function navigation ()
	{
		
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