<?php
    //require files
    require_once('models/user.php');
    require_once('models/agent.php');
    require_once('config/json.php');
    

    //method

    if ($_SERVER['REQUEST_METHOD']=='GET') {

        
        # code...
        if ($action =='') {
            if($parameters == ''){
                if(User::belongsToRole($headers['username'],['SA','SUPER'])){ 
                    //get all
                    echo json_encode(array(
                        "status"=>0,
                        "agents"=>Json::listToArray(Agent::getAll())
                    )); 
                }else{
                    echo json_encode(array(
                        'status'=>502,
                        'errorMessage'=>'Unauthorized Access'
                    ));
                }
            }else{
                $agent = new Agent($parameters);
                echo json_encode(array(
                    "status"=>0,
                    "agent"=>json_decode($agent->ToJson())
                )); 

            }
        }else {
            //authorization
            if(User::belongsToRole($headers['username'],['SA','SUPER'])){
                if($action == 'available'){
                   echo json_encode(array(
                       "status"=>0,
                       "availableAgents"=>Json::listToArray(Agent::getAvailableAgents())
                   )); 
                }
                if($action == 'active'){
                   echo json_encode(array(
                       "status"=>0,
                       "activeAgents"=>Json::listToArray(Agent::getActiveAgents())
                   )); 
                }
            }else{
                echo json_encode(array(
                    'status'=>502,
                    'errorMessage'=>'Unauthorized Access'
                ));
            }
        }
        
    }
?>              