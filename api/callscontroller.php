<?php
    //require files
    require('models/call.php');
    //Get
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //list
        if($action == ''){
            //get all
            if($parameters == ''){
                //get today calls
                echo json_encode(array(
                    "status"=>0,
                    "calls"=>json_decode(Call::getAllToJson())
                ));
            }else{
                $call = new Call($parameters);
                echo json_encode(array(
                    "status"=>0,
                    "call"=>json_decode($call->toJson)
                ));
            }
        }
        //hourly totals
        if($action == 'hourlytotal'){
            echo 'get call totals by hour';
        }
        //dailytotals
        if($action == 'today'){ 
            //get today calls
            echo json_encode(array(
                "status"=>0,
                "todayCalls"=>json_decode(Call::getAllTodayToJson())
            )); 
        }
    }
    //Post
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Receive Call
        if($action == 'receive'){
            echo Call::receive($parameters);
        }
        if($action == 'end'){
            echo Call::end($parameters);
        }
    } 
?>