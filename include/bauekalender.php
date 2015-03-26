<?php

function BaueMonatsKalender ($jahr, $monat) {
    $internesJahr = $jahr;
    $internerMonat = $monat;
    $generiereZeitstempelDesErstenWochentagsImMonat = mktime(1,0,0,$internerMonat,1,$internesJahr,0);       //Generierung des Zeitstempels für den ersten Wochentag im angegebenen Monat
    $ersterWochentagImMonat = date("w", $generiereZeitstempelDesErstenWochentagsImMonat);   //Angabe über den ersten Wochentag in Numerischerform
    //$anzahlTageDesMonats=date("t", $internerMonat);                                       //Gibt einen Wert zwischen 28 und 31 zurück, jenachdem wie lang der Monat ist
    $anzahlTageDesMonats= date("t", mktime(1,1,1,$internerMonat));
    $wurdeErsterWochentagGeschrieben = false;                                               //Boolscherwert der auf true gesetzt wird, sobald der erste Tag in den Kalender geschrieben wurde
    $beginneMitNummerierung = false;                                                        //Wurde mit der Nummerierung im Kalender schon begonnen wird diese Variable auf True gesetzt
    $tagDatum=1;                                                                            //Zähl Variable um eine Wochentagnummerierung im generierten Kalender einzufügen
    $wochentagNamenArray = array(                                                           //Erstellung der Tabelle für die Monatsansicht
            "Montag", 
            "Dienstag", 
            "Mittwoch", 
            "Donnerstag", 
            "Freitag", 
            "Samstag", 
            "Sonntag"); 
    
    // Damit der Sonntag als 7ter Tag der Woche gilt und nicht der 0te
    if ($ersterWochentagImMonat == 0 )
    {
        $ersterWochentagImMonat+=7;
    }

    //Beginn der Kalender Tabelle
    echo "<table border=1>";

    //Schleife für die Zeilenanzahl
    //Anzahl der Zeilen sind 7
    for ( $zeilen = 1 ; $zeilen <= 7 ; $zeilen++) {
        echo "<tr>";
    
        //Schleife für die Spaltenanzahl
        //Da eine Woche sieben Tage hat hat die Tabelle auch sieben Spalten,
        //wobei die erste Spalte mit Montag beginnt
        for ($spalten = 1 ; $spalten <= 7 ; $spalten++) {
        
            //Prüft ob gerade die erste Zeile der Tabelle geschrieben wird,
            //wenn ja wird der Wochentagname geschrieben
            if($zeilen == 1) 
            {
                echo "<td> {$wochentagNamenArray[$spalten-1]} </td>";
            } 
            else 
            {
                if ($ersterWochentagImMonat == $spalten && $wurdeErsterWochentagGeschrieben == false) 
                {
                    echo "<td>$tagDatum</td>";
                    $tagDatum++;
                    $beginneMitNummerierung = true;
                    $wurdeErsterWochentagGeschrieben= true;
                } 
                elseif ($beginneMitNummerierung == true && $tagDatum <= $anzahlTageDesMonats)
                {
                    echo "<td>$tagDatum</td>";
                    $tagDatum++;
                }
                else 
                {
                    echo "<td>&nbsp;</td>";
                }
            
            }
        }
    }
    echo "</table>";
        
}

?>