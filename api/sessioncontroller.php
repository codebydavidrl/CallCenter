<?php
require('models/session.php');
    //post
    if ($_SERVER['REQUEST_METHOD']=='POST') {
        # end call
        if($action == 'start'){
            $headers = getallheaders();
            //check headers
            if (isset($headers['agentid']) && isset($headers['pin'])&& isset($headers['workstationid'])) {
                echo 'start session : '.$headers['agentid'].' : '.$headers['pin'].' : '.$headers['workstationid'];
                # code...
            }else
                echo json_encode(array('status'=>999,'errorMessage'=>'missing parameters'));

        }
        if($action == 'endsession'){
            echo Session::endsession($parameter);
        }
        if ($action=='endcall') {
            # code...
            if ($parameters !='') {
                # code...
                echo Session::endcall($parameters);
            }
            
        }
    }

?>