<html>
<head>
<title>Index.pho</title>
<?php include 'include/bauekalender.php';
      include 'include/feiertagsberechnung.php';
      include 'include/loginfunktion.php';
      include 'include/monatindeutsch.php';?>
      <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<!-- <form action="<?//php $_SERVER['PHP_SELF']?>">
<select name="datum"> 
<?php 
    /* $monatjahr = date("m.Y");
    for ( $i = 1 ; $i <= date("t") ; $i++)
    {
        if ($i<10) {
            $eintrag = "0$i";
        } else {
            $eintrag = $i;
        } 
        if ((date("D",$eintrag.$monatjahr) == "Sun") && (date("D",$eintrag.$monatjahr) == "Sat"))
        {
            
        } else {
            echo "<option value=$eintrag.$monatjahr>$eintrag.$monatjahr";
        }
        
    } */
?>




</select>

<?php //echo date("D",$eintrag.$monatjahr);
      //echo $eintrag.$monatjahr; */?>
</form> -->
<table border=1>
<?php 

for ($i = 8; $i <= 12; $i++) {

    echo "<tr><td>".MonatInDeutsch($i)."</td></tr>";
    echo "<tr><td>".BaueMonatsKalender(2015, $i)."</td></tr>";
}
?>
</table>


</body>
</html>

