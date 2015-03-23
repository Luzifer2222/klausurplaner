<?php

function Login($benutzername, $passwort) {
    
    $verschluesseltesPasswort = hash('sha256', $passwort);
    
    if ($benutzername=='dthielking' && hash('sha256', $passwort ) == 'd3751d33f9cd5049c4af2b462735457e4d3baf130bcbb87f389e349fbaeb20b9') {
        return true;
    }
    else 
    {
        return "Deine mama!";
    }
    
    
}
?>