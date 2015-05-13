<nav>
	<ul>
		<li class="user"><a href="#">Benutzerbereich</a>
			<ul>
				<li><a href="./index.php?seite=passwort_aendern.php">Passwort ändern</a></li>
			</ul></li>
		<li class="klassen"><a href="#">Klassen</a>
			<ul>
				<li><a href="./index.php?seite=stundenplan_anzeigen.php">Anzeigen</a></li>
			</ul></li>
		<li class="kalender"><a href="#">Kalender</a>
			<ul>
				<li><a href="./index.php?seite=kalender_anzeigen.php">Anzeigen</a></li>
				<li><a href="./index.php?seite=neuer_termin.php">Eintrag</a></li>
			</ul></li>
		<li class="admin"><a href="#">Administration</a>
			<ul>
				<li><a href="./index.php?seite=globale_termine_anlegen.php">Globale Termine</a></li>
				<li><a href="./index.php?seite=klasse_anlegen.php">Klassen</a></li>
				<li><a href="./index.php?seite=fach_anlegen.php">Fächer</a></li>
				<li><a href="./index.php?seite=lehrer_anlegen.php">Lehrer</a></li>
				<li><a href="./index.php?seite=abteilung_anlegen.php">Fachbereiche</a></li>
				<li><a href="./index.php?seite=stundenplan_bearbeiten.php">Stundenpläne</a></li>
				<li><a href="./index.php?seite=gewichtung_anlegen.php">Gewichtung</a></li>
			</ul></li>
		<!-- Begin der Prüfung, ob Benutzer angemeldet ist -->
		<?php
		$sessionPruefe = new sessionkontrolle();
		
		if ($sessionPruefe->navigation())
		:
			?>
		<li class="login"><a href="#">Logout</a>
			<ul>
				<li>
					<form action="./index.php?seite=einausloggen.php" method="post" name="abmeldeformular" class="abmeldeformular">
						<fieldset>
							<legend>Eingeloggt als:</legend>
							<p><?php echo "Benutzername: " . $_SESSION['benutzername']?></p>
							<input type="submit" name="abmelden" value="Abmelden">
						</fieldset>
					</form>
				</li>
			</ul></li>					
		
		
		
		<?php
		else
		:
			?>
		<li class="login"><a href="#">Login</a>
			<ul>
				<li>
					<form action="./index.php?seite=einausloggen.php" method="post" name="anmeldeformular" class="anmeldeformular">
						<fieldset>
							<legend>Anmeldeformular</legend>
							<p>
								<label for="benutzername">Benutzername:</label><input type="text" name="benutzername" id="benutzername" class="loginfeld">
							</p>
							<p>
								<label for="passwort">Passwort:</label><input type="password" name="passwort" id="passwort" class="loginfeld">
							</p>
							<input type="submit" name="anmelden" value="Anmelden">
						</fieldset>
					</form>
				</li>
			</ul></li>
		
		
		<?php
			endif;
		?>		
	</ul>
</nav>