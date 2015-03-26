
<html>
<head>
<title>Class Test Scheduler 'CTS' by Daniel Thielking, Robin Gebhardt, Pascal Lawitzky</title>
<?php
include '../config/database.conf.php';
?>

<link href="../style/style.css" rel="stylesheet" type="text/css" />

<?php 

$con = mysqli_connect($database_conf['host'],$database_conf['user'],$database_conf['password'],$database_conf['database']);

if(isset($_POST['abtanlegen']))
{
  
  $query = "insert into abteilung (name) values ('". $_POST['abtname']."');";
  
  mysqli_query($con, $query);
  
  if (mysqli_affected_rows($con) > 0 ) {
      echo "Es wurde 1 Datensatz erzeugt";
  }
  else { 
      echo "Es ist ein Fehler aufgetreten <br />Es wurde kein Datensatz erzeugt.";
  }  
    
}
elseif (isset($_POST['abtloeschen']))
{
    $query = "delete from abteilung where abteilungID = ".$_POST['id'].";";
    mysqli_query($con, $query);
    if (mysqli_affected_rows($con)>0) {
        echo "Es wurde der Datensatz mit der ID: ".$_POST['id']. " gelöscht.";
    }
    else 
    {
        echo "Fehler! es wurde kein Datensatz gelöscht";    
    }
}
$datenbankAusgabe = mysqli_query($con, "select * from abteilung");

mysqli_close($con);
?>
</head>
<body>
<table border="1">
  <tr>
    <td>
      <h1>Abteilung anlegen</h1>
      <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
      <p>Abteilung<input type="text" min="4" maxlength="50" name="abtname" /></p>
      <p><input type="submit" name="abtanlegen" value="Abteilung Anlegen" /></p>
      </form>
    </td>
    <td>
      <h1>Abteilung löschen</h1>
      <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
      <p>Angabe der ID <input type="number" name="id" /></p>
      <p><input type="submit" name="abtloeschen" value="Löschen" /></p>
      </form>
    </td>
  </tr>
</table>
<br /><br /><br />
<table>
  <tr>
    <td>AbteilungsID</td>
    <td>Abteilungsname</td>
   <?php 
   while($ausgabe = mysqli_fetch_assoc($datenbankAusgabe))
   {
   echo "<tr>";
   echo "<td>".$ausgabe["abteilungID"]."</td>"."<td>".$ausgabe["name"]."</td>";
   echo "</tr>";
   }
   ?>
</table>
</body>

</html>