<?php

class sessionkontrolle
{

	private $anmeldezeit = 3600;

	function AdminBereich ()
	{
		// Prüfen ob das Array der Session initialisiert wurde
		if (!isset($_SESSION['benutzername']) && !isset($_SESSION['administrator']))
		{
			// die Session nicht initialisiert wurde.
			exit ("<p class=\"error\">Sie haben keinen Zugang zu der Seite!<br>Sie sind nicht angemeldet.</p>");
		
		}
		
		if ($_SESSION['administrator'] != true)
		{
			
			// Programm abbruch, da der Benutzer kein Administrator ist
			exit ("<p class=\"error\">Sie haben keinen Zugang zu der Seite!<br /> Sie sind kein Administrator</p>");
		}
	
	}

	function UserBereich ()
	{
		// Prüfen ob das Array der der Session initialisiert wurde
		if (!isset($_SESSION['benutzername']))
		{
			// Programm abbruch, da die Session nicht initialisiert wurde.
			exit ("<p class=\"error\">Sie haben keinen Zugang zu der Seite!<br>Sie sind nicht angemeldet.</p>");
		}
		
		// Neu setzen der Anmeldezeit für jeden Seiten aufruf.
		$_SESSION['anmeldezeit'] = time();
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

	function setAnmeldezeit ($anmeldezeit)
	{
		$this->anmeldezeit = $anmeldezeit;
	}
	
	function pruefeAnmeldezeit(){
		if (isset($_SESSION['anmeldezeit']))
		{
			if ($_SESSION['anmeldezeit'] + $this->anmeldezeit < time())
			{
				// Löschen der $_SESSION-Arrays
				$_SESSION = array();
				return "<p class=\"error\">Sie wurden automatisch ausgeloggt. Anmeldezeit überschritten!";
			}
		}
		else
		{
			// Neu setzen der Anmeldezeit für jeden Seiten aufruf.
			$_SESSION['anmeldezeit'] = time();
		}
	}

}

?>