<?php
    //require files
    require('models/call.php');
    //Get
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //list
        if($action == ''){
            //get all
            if(!empty($parameters)){  
                $call = new Call($parameters);
                echo json_encode(array(
                    "status"=>0,
                    "call"=>json_decode($call->toJson)
                ));
            }
        }
        //calls on queue 
        if($action == 'queue'){
            //get today calls
            echo json_encode(array(
                "status"=>0,
                "queue"=>json_decode(Call::getCallsOnQueueToJson())
            ));
        }
        //calls active
        if($action == 'active'){
            //get today calls
            echo json_encode(array(
                "status"=>0,
                "active"=>json_decode(Call::getActiveCallsToJson())
            )); 
            
        }
        //today calls
        if($action == 'today'){ 
            //get today calls
            echo json_encode(array(
                "status"=>0,
                "today"=>json_decode(Call::getTodayCallsTojson())
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