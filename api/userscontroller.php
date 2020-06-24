<?php
//require files
    require_once('models/user.php'); 
    require_once('./config/json.php');
    //GET
    if ($_SERVER['REQUEST_METHOD']== 'GET') {
        if($action ==''){
            if ($parameters=='') {
                // //get all
                echo json_encode(array(
                    'status'=>0,
                    'users'=>Json::listToArray(User::getAll())
                ));  
            }else{ 
                $user =new User($parameters);
                //get one user
                echo json_encode(array(
                    'status'=>0,
                    'user'=>json_decode($user->toJson())
                ));  
            }
        }
        

        if($action == "login"){
            $headers = getallheaders();
            if(isset($headers['username']) && isset($headers['password'])){
                $userName = $headers['username'];
                $password = $headers['password'];
                try {
                    //code...
                    $u = new User($userName,$password);
                    echo json_encode(array('status'=>200,'user'=>json_decode($u->toJson())));

                } catch (AccessDeniedException $ex) {
                    echo json_encode(array('status'=>400,'errorMessage'=>$ex->getMessage()));
                }
            }else{
                echo json_encode(array('status'=>999,'errorMessage'=>'Missing security headers'));
            }
        }
    }
    //post
    if ($_SERVER['REQUEST_METHOD']=='POST') {
        
    }
    //put
    if ($_SERVER['REQUEST_METHOD']=='PUT') {
        
    }
    //delete
    if ($_SERVER['REQUEST_METHOD']=='DELETE') {
        
    }
?>