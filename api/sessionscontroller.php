<?php
    require_once('models/session.php');
    require_once('config/json.php');
    if($_SERVER['REQUEST_METHOD']=='GET'){
        if(!empty($action)){
            if($action == 'active'){

            }
        }else{ 
            if(!empty($parameters)){
                $session = new Session($parameters);
                echo $session->toJson();
            }else{
                //if there is not action
                //Show all
                echo json_encode(Json::listToArray(Session::getAll()));
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
        if($action == 'endsession'){
            echo Session::endsession($parameter);
        }
        if ($action=='endcall') {
            $headers = getallheaders();
            if (isset($_POST['sessionid'])) { 
                echo Session::endcall($_POST['sessionid']);
            }else{
                echo "empty parameters";
            }
            
        }
    }

?>