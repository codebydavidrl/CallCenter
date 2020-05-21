<?php
    require_once('models/agent.php');
    

    //method

    if ($_SERVER['REQUEST_METHOD']=='GET') {
        # code...
        if ($parameters!='') {
            # code...
            try {
                $a=new Agent($parameters);
                echo json_encode(array(
                    'status'=>0,
                    'agent'=>json_decode($a->toJson())
                ));
            } catch (RecordNotFoundException $ex) {
                //throw $th;
                echo json_encode(array(
                    'status'=>1,
                    'errorMessage'=> $ex->getMessage()
                ));
            }
        }else {
            echo json_encode(array(
                'status'=>0,
                'agents'=>json_decode(Agent::getAllToJson())
            ));
        }
    }
?>              