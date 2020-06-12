<?php
    require_once('models/session.php');
    require_once('config/json.php');
    if($_SERVER['REQUEST_METHOD']=='GET'){
        if(!empty($action)){
            if($action == 'active'){
                echo json_encode(array(
                    "status"=>0,
                    "active"=>Json::listToArray(Session::getActive())
                )); 
            }
            if($action == 'available'){
                echo json_encode(array(
                    "status"=>0,
                    "available"=>Json::listToArray(Session::getAvailable())
                )); 
            }
        }else{ 
            if(!empty($parameters)){
                $session = new Session($parameters);
                echo $session->toJson();
            }else{
                //if there is not action
                //Show all
                echo json_encode(array(
                    "status"=>0,
                    "sessions"=>Json::listToArray(Session::getAll())
                ));
            }
            
        }
    }



    //post
    if ($_SERVER['REQUEST_METHOD']=='POST') {
        # end call
        if($action == 'start'){
            $headers = getallheaders();
            //check headers
            if (isset($headers['agentid']) && isset($headers['pin'])&& isset($headers['workstationid'])) {
                echo Session::start($headers['agentid'],$headers['pin'],$headers['workstationid']);
            }else{
                echo json_encode(array('status'=>999,'errorMessage'=>'missing parameters'));
            }

        }
        if($action == 'end'){
            echo Session::end($parameters);
        }
        if ($action=='endcall') {
            $headers = getallheaders();
            echo Session::endcall($parameters); 
            
        }
    }

?>