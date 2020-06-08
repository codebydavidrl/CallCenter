<?php
//require files
    require_once('models/totalsHour.php');
    //GET
    if ($_SERVER['REQUEST_METHOD']== 'GET') {
        
        if ($parameters=='') {
            // //get all
            echo json_encode(array(
                'status'=>0,
                'totalsHour'=>json_decode(TotalsHours::getAlltoJson())
            )); 
            
        }
    } 
?>