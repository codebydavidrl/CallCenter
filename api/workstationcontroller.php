<?php
    require_once('models/workstation.php');

    //method

    if ($_SERVER['REQUEST_METHOD']=='GET') {
        # code...
        if ($parameters!='') {
            # code...
            try {
                $a=new WorkStation($parameters);
                echo json_encode(array(
                    'status'=>0,
                    'workstation'=>json_decode($a->toJson())
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
                'workstations'=>json_decode(WorkStation::getAllToJson())
            ));
        }
    }
?>         