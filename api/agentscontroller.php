<?php
    require_once('models/agent.php');
    

    //method

    if ($_SERVER['REQUEST_METHOD']=='GET') {
        # code...
        if ($action =='') {
            if($parameters == ''){
                //get all
                echo json_encode(array(
                    "status"=>0,
                    "agents"=>json_decode(Agent::getAllToJson())
                )); 
            }else{
                $agent = new Agent($parameters);
                echo json_encode(array(
                    "status"=>0,
                    "agent"=>json_decode($agent->ToJson())
                )); 

            }
        }else {
             if($action == 'available'){
                echo json_encode(array(
                    "status"=>0,
                    "availableAgents"=>json_decode(Agent::getAvailableAgentsToJson())
                )); 
             }
             if($action == 'active'){
                echo json_encode(array(
                    "status"=>0,
                    "activeAgents"=>json_decode(Agent::getActiveAgentsToJson())
                )); 
             }
        }
    }
?>              