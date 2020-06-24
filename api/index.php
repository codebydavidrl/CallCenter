<?php

    //headers
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    //allow method
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    $headers = getallheaders();
    //Require Files
    require_once('./config/security.php');
    //request url
    $requestUri = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])));
    //set default time zone 
    date_default_timezone_set ( 'America/Tijuana');


    

    //split ur in parts
    $uriparts = explode('/', $requestUri);
    //validate url
    if (sizeof($uriparts)==3|| sizeof($uriparts)==4) {
        //controller
        $controller=$uriparts[1];
        //action
        if (sizeof($uriparts)==4) {
            $action=$uriparts[2];
            $parameters= $uriparts[3];
        }else{
            $action='';
            $parameters=$uriparts[2];
        } 
        //check security 
        $grantAccess=false;
        //Every API except login
        if($controller != 'users' || $action != 'login'){
            if(isset($headers['username']) && isset($headers['token'])){
                //Check token belongs to user and it's valid
                if($headers['token'] == Security::generateToken($headers['username']))
                    $grantAccess=true;
                else
                    echo json_encode(array('status'=>501,'Invalid or expired security token'));

            }else{
                echo json_encode(array('status'=>500,'errorMessage'=>'Missing security headers'));
            }
        }else{
            $grantAccess=true;
        }
        
        //go to controller
        if($grantAccess){ 
            $controller.='controller.php';
            if (file_exists($controller)) {
                require_once($controller);
            }else{
                echo json_encode(array('status'=> 998,'errorMessage'=>'invalid Controller'));
            }
        }

        
    }else{
        echo json_encode(array('status'=> 999,'errorMessage'=>'invalid URL'));
    }
    
    
?>