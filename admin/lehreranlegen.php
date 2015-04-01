<?php 
  //Starten/Wiederaufnehmen einer Session
  session_start();

  //Prüfen ob das Array der der Session initialisiert wurde
  if (!isset($_SESSION['benutzername'])) 
  {
    //Programm abbruch, da die Session nicht initialisiert wurde.
    exit("<p>Benutzername oder Passwort falsch!</p>
          <p>Sie haben keinen Zugang zu der Seite! <br><a href=\"/login.php\">Login Seite</a>");
  }
?>

<html>
<head>
  <title>Class Test Scheduler 'CTS' by Daniel Thielking, Robin Gebhardt, Pascal Lawitzky</title>
  <!-- Angaben zum Zeichensatz, immer UTF-8 -->
    <meta charset="utf-8"> <!-- Angabe des Zeichensatzes für HTML5 -->
    <meta http-eqiv="content-type" content="text/html; charset=utf-8"> <!-- Angabe des Zeichensatzes für ältere Browser -->
    
    <!-- Angabe wie die Seite sich verhalten soll -->
    <meta http-equiv="expires" content="0"> <!-- Seite wird immer von Server geholt und nicht aus dem cache geladen -->
        
    <!-- Angabe zu den Autoren der Website und andere Informationen -->
    <meta name="author" content="Daniel Thielking, Robin Gebhardt, Pascal Lawitzky">
    <meta name="project-group" content="PHPmeetsSQL G3">
    <meta name="description" content="Klausurplaner Software">
    
    <!-- Einfügen des FavoritenIcons -->
    <link rel="icon" href="../pictures/favicon/favicon.png" sizes="16x16 32x32" type="images/png">
    <!-- Einfügen der CSS-Dateine -->
    <link href="../style/style.css" rel="stylesheet" type="text/css" />
    
<?php 
include '../include/loginfunktion.php';
include '../config/database.conf.php';

  //Verbindung zu Datenbank herstellen
  $link = mysqli_connect($database_conf['host'],$database_conf['user'],$database_conf['password'],$database_conf['database']);
  
  //Überprüfung ob der Submitbutton gedrückt wurde
  //Zuständig für das Einfügen eines neuen Lehrers
  //in die Lehrer Tabelle
  if (isset($_POST['benanlegen']))
  {
      //Überprüfung ob alle Felder des Einfüge Formulars ausgefüllt wurden
      if ($_POST['vname']!= "" && $_POST['nname']!= "" && $_POST['kuerzel']!= "" && $_POST['bname']!= "" && $_POST['pwd']!= "") {
          
          //Erstellen der Einfüge anweisung in SQL
          $insertquery = "insert into lehrer ";
          $insertquery .= "(vorname, nachname, kuerzel, benutzername, passwort, abteilungID) values";
          $insertquery .="('".$_POST['vname']."', '".$_POST['nname']."', '".$_POST['kuerzel']."', '".$_POST['bname']."', '";
          $insertquery .= verschluesselLogin($_POST['pwd'])."', '".$_POST['abteilung']."');";
          
          //Einfügen der Formulardaten in die Lehrertabelle
          mysqli_query($link, $insertquery);
          
          //Überprüfung ob der Datensatz angelegt wurde
          if(mysqli_affected_rows($link)>0)
          {
              //Speichern des Ausgabestrings in eine Variable
              $ausgabe = "<p class=\"erfolgreich\">Es wurde 1 Datensatz angelegt.</p>";
          }
      } 
      else 
      {
          //Speichern des Fehlerstrings in eine Variable
          $ausgabe = "<p class=\"error\">Alle Felder müssen ausgefüllt werden!</p>";
      }
  
  }
  
  //Überprüfung ob der Button 'Lösche' gelöscht gedrückt wurde
  if (isset($_POST['loeschelehrer']) && isset($_POST['loesche'])) 
  {
      
      //Speichern der delete Abfrage und durchführung der Abfrage
      $abfloeschelehrer = "delete from lehrer where lehrerID = ".$_POST['loesche'].";";
      mysqli_query($link, $abfloeschelehrer);
      
      //
      if (mysqli_affected_rows($link)>0) 
      {
          $ausgabe = "<p class=\"erfolgreich\">Es wurde der Datensatz mit der ID: ".$_POST['loesche']. " gelöscht.</p>";
      }
  }
  
  
  //Einfache Abfragen für das Extrahieren der Abteilungen und der Lehrer
  $abfrageabteilung = "select * from abteilung;";
  $abfragelehrer = "select l.lehrerID, l.vorname, l.nachname, l.kuerzel, l.benutzername, a.name ";
  $abfragelehrer .= "from lehrer l, abteilung a ";
  $abfragelehrer .= "where l.abteilungID = a.abteilungID";
  
  //Ergebnis der Abfrage aus $abfragelehrer
  $ergabfragerlehrer = mysqli_query($link, $abfragelehrer);
?>

</head>
<body>

  <form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="lehrereinfuegen" class="lehrereinfuegen">
    <fieldset >
      <legend>Einfügen des Lehrpersonals in das 'CTS'</legend>
      <p><label for="vorname">Vorname: <br><input type="text" maxlength="50" name="vname" id="vorname"></label></p>
      <p><label for="nachname">Nachname: <br><input type="text" maxlength="50" name="nname" id="nachname"></label></p>
      <p><label for="kuerzel">Kürzel: <br><input type="text" min="4" maxlength="5" name="kuerzel" id="kuerzel"></label></p>
      <p><label for="abteilung">Abteilung: 
        <br><select name="abteilung" id="abteilung">
          <?php 
            $ergabteilungdb = mysqli_query($link, $abfrageabteilung);
            while($daten = mysqli_fetch_object($ergabteilungdb))
            {
              echo "<option value=$daten->abteilungID>".$daten->name."</option>";
            }
          ?>
        </select></label></p>
      <p><label for="benutzername">Benutzername: <br><input type="text" min="4" maxlength="15" name="bname"></label></p>
      <p><label for="passwort">Passwort: <br><input type="password" min="5" name="pwd"></label></p>
      <p><input type="submit" name="benanlegen" value="Lehrer anlegen"></p>
    </fieldset>
  </form>
  
  <?php 
    //Ausgabe ob eintrag in die Datenbank erfolgreich war.
    if (isset($ausgabe)) {
        echo $ausgabe;
    }
  ?>
  
  <table>
    <tr>
      <th>LehrerID</th>
      <th>Vorname</th>
      <th>Nachname</th>
      <th>Kürzel</th>
      <th>Benutzername</th>
      <th>Abteilung</th>
      <th><form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
          <input type="submit" name="loeschelehrer" value="Löschen?"></th>
    </tr>
    <?php 
      while ($lehrertabelle = mysqli_fetch_object($ergabfragerlehrer))
      {
        echo "<tr>";
        echo "<td>".$lehrertabelle->lehrerID."</td>";
        echo "<td>".$lehrertabelle->vorname."</td>";
        echo "<td>".$lehrertabelle->nachname."</td>";
        echo "<td>".$lehrertabelle->kuerzel."</td>";
        echo "<td>".$lehrertabelle->benutzername."</td>";
        echo "<td>".$lehrertabelle->name."</td>";
        echo "<td><input type=\"radio\" name=\"loesche\" value=\"".$lehrertabelle->lehrerID."\"></td>";
        echo "</tr>";
        
      }
    ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="loeschelehrer" value="Löschen?"></form></td>
    </tr>
  </table>
</body>

<?php 
//Schließen der Datenbank am Ende der Seite
mysqli_close($link);  
?>
</html>