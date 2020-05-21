<?php
    //require files
    require('models/call.php');
    //Get
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //list
        if($action == ''){
            //get all
            if($parameters == ''){
                echo 'get all the calls';
            }
            else{
                echo 'get one call';
            }
        }
        //hourly totals
        if($action == 'hourlytotal'){
            echo 'get call totals by hour';
        }
        //dailytotals
        if($action == 'dailytotals'){
            echo 'get call totals by day';
        }
    }
    //Post
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Receive Call
        if($action == 'receive'){
            echo Call::receive($parameters);
        }
    }
    //Put
    //Delete
?>