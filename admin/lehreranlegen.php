
<html>
<head>
<title>Class Test Scheduler 'CTS' by Daniel Thielking, Robin Gebhardt, Pascal Lawitzky</title>
<?php 
include '../include/loginfunktion.php';
include '../config/database.conf.php';
?>

<link href="../style/style.css" rel="stylesheet" type="text/css" />

<?php 
  $con = mysqli_connect($database_conf['host'],$database_conf['user'],$database_conf['password'],$database_conf['database']);
  $query = "select * from abteilung;"; 
?>

</head>
<body>
<table border="1">
  <tr>
    <td>
      <h1>Lehrere Anlegen</h1>
      <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
        <p>Vorname<input type="text" maxlength="50" name="vname"></p>
        <p>Nachname<input type="text" maxlength="50" name="nname"></p>
        <p>Kürzel<input type="text" min="4" maxlength="5" name="kuerzel"></p>
        <p>Abteilung
          <select name="abteilung">
          <?php 
            $ergabteilungdb = mysqli_query($con, $query);
            while($daten = mysqli_fetch_object($ergabteilungdb))
            {
                echo "<option value=$daten->abteilungID>".$daten->name."</option>";
            }
          ?>
          </select></p>
        <p>Benutzername<input type="text" min="4" maxlength="15" name="bname"></p>
        <p>Passwort<input type="password" min="5" name="pwd"></p>
        <p><input type="submit" name="benanlegen" value="Anlegen"></p>
      </form>
    </td>
  </tr>
</table>

</body>
<?php 

  if (isset($_POST['benanlegen']))
  {
      if (!is_null($_POST['pwd'])) {
          $insertquery = "insert into lehrer (vorname, nachname, kuerzel, benutzername, passwort, abteilungID) values";
          $insertquery .="(".$_POST['vname'].",". $_POST['nname'].",". $_POST['kuerzel'].",". $_POST['bname'].",";
          $insertquery .= verschluesselLogin($_POST['pwd']).",".$_POST['abteilung'].";";
          mysqli_query($con, $insertquery);
          if(mysql_affected_rows($con)>0)
          {
              echo "Es wurde 1 Datensatz angelegt.";
          }
      } else {
          echo "Das Passwort darf nicht leer sein.";
      }
      
  }
  mysqli_close($con);
?>
</html>