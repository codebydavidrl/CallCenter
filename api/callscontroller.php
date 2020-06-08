<?php
    //require files
    require_once('models/call.php');
    require_once('config/json.php');
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
                "queue"=>Call::getCallsOnQueueToJson()
            ));
        }
        //calls active
        if($action == 'active'){
            //get today calls
            echo json_encode(array(
                "status"=>0,
                "active"=>Json::listToArray(Call::getActiveCalls())
            )); 
            
        }
        //today calls
        if($action == 'today'){ 
            //get today calls
            echo json_encode(array(
                "status"=>0,
                "today"=>Json::listToArray(Call::getTodayCalls())
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