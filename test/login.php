<html>

	<head>
		<title>LoginPage</title>
		<?php include 'LoginFunktion.php';?>
	</head>
	<body>
		<form action="login.php" method="post">
			<p>Benutzername <input type="text" name="benutzername" size="20" /></p>
			<p>Passwort     <input type="password" name="passwort" size="20" /></p>
			<p><input type="submit" name="senden" value="Login" /><input type="reset" name="reset" value="Cancel" /></p>
			</form>
			
			<?php 
			
			if(isset($_POST['senden']))
			{
				if ( Login($_POST['benutzername'], $_POST['passwort']) == true )
				{
					echo "Eingeloggt";
				}
				else
				{
					echo "Falscher Benutzername oder Passwort.";
				}
				
			}
			?>
	</body>

</html>