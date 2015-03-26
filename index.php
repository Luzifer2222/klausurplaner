<html>

<head>
<title>Class Test Scheduler 'CTS' by Daniel Thielking, Robin Gebhardt, Pascal Lawitzky</title>
<?php include './include/LoginFunktion.php';?>

<link href="../style/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
<p><input type="text" min="4" maxlength="15" value="Benutzername" name="benutzername"></p>
<p><input type="password" min="5" maxlength="15" value="Passwort" name="passwort"></p>
<p><input type="submit" name="einloggen" value="Login"> <input type="reset" name="reset" value="Zurücksetzen"></p>
</form>



</body>

</html>