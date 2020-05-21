<?php

    //headers
    header('Access-Control-Allow-Origin: *');
    //allow method
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    //request url
    $requestUri = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])));

    

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
        //go to controller
        $controller.='controller.php';
        if (file_exists($controller)) {
            require_once($controller);
        }else{
            echo json_encode(array('status'=> 998,'errorMessage'=>'invalid Controller'));
        }

        
    }else{
        echo json_encode(array('status'=> 999,'errorMessage'=>'invalid URL'));
    }
    
    
?>