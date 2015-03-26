<html>
<head>
<title>abteilung anlegen</title>
<?php 

include './config/database.conf.php';

?>
</head>
<body>
<p>Bitte geben sie den Namen der neuen Abteilung ein.</p>
<form method="post" action="<?php $_SERVER['PHP_SELF']?>">
<p><input type="text" name="abtname" min="4" /></p>
<p><input type="submit" value="Anlegen" name="submit" /> <input type="reset" name="Zurücksetzten" /></p>
</form>

<table>
<tr>
  <td>AbteilungsID</td>
  <td>Abteilung</td>
  <td>Leiter</td>
<?php 
$con = mysqli_connect($database_conf['host'], $database_conf['user'], $database_conf['password']);
mysqli_select_db($con, 'klausurplaner');
$abf = mysqli_query($con, 'select abteilung.AbteilungID ,abteilung.Name , lehrer.Kuerzel from lehrer, abteilung where lehrer.LehrerID = abteilung.LeiterID;');
echo "Gefundene Datensätze: ". mysqli_num_rows($abf);

while ($dsatz = mysqli_fetch_assoc($abf)) {
    echo "<tr>";
    echo "<td>".$dsatz['AbteilungID']."</td>";
    echo "<td>".$dsatz['Name']."</td>";
    echo "<td>".$dsatz['Kuerzel']."</td>";
}
?>
</table>

</body>

</html>