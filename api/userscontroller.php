<?php
//require files
    require_once('models/user.php');
    //GET
    if ($_SERVER['REQUEST_METHOD']== 'GET') {
        
        if ($parameters=='') {
            // //get all
            echo json_encode(array(
                'status'=>0,
                'users'=>json_decode(User::getAlltoJson())
            )); 
            
        }
    }
    //post
    if ($_SERVER['REQUEST_METHOD']=='POST') {
        
    }
    //put
    if ($_SERVER['REQUEST_METHOD']=='PUT') {
        
    }
    //delete
    if ($_SERVER['REQUEST_METHOD']=='DELETE') {
        
    }
?>