<?php
//require files
    require_once('models/user.php');
    require_once('./config/json.php');
    //GET
    if ($_SERVER['REQUEST_METHOD']== 'GET') {
        
        if ($parameters=='') {
            // //get all
            echo json_encode(array(
                'status'=>0,
                'users'=>Json::listToArray(User::getAll())
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