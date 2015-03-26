<?php
function MonatInDeutsch ($monat) {
    
    $engMonat = date("M", mktime(1,1,1,$monat));
    
    switch ($engMonat) {
        case "Jan":
        return "Januar";
        break;
        case "Feb":
            return "Februar";
        break;
        case "Mar":
            return "März";
        break;
        case "Apr":
            return "April";
        break;
        case "May":
            return "Mai";
        break;
        case "Jun":
            return "Juni";
        break;
        case "Jul":
            return "Juli";
        break;
        case "Aug":
            return "August";
        break;
        case "Sep":
            return "September";
        break;
        case "Oct":
            return "Oktober";
        break;
        case "Nov":
            return "November";
        break;
        case "Dec":
            return "Dezember";
        break;
        default:
            return 1;
        break;
}
   
}
?>