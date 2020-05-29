<?php
    require_once('call.php');
    require_once('agent.php');
    
    class Today{
        public static function get(){
            echo json_encode(array(
                'status'=>0, 
                'today'=>json_decode(json_encode(array(
                    "agents"=> Agent::getActiveAgentsByHour(),
                    "calls"=>Call::getCallsByHour(),
                    "averageHandleTime"=> Call::getAverageHandleTimeByHour(),
                    "averageWaitTime"=> Call::getAverageWaitTimeByHour()
                )))
            ));
        }
    }

?>