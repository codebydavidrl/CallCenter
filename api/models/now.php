<?php
require_once('agent.php');
require_once('totalsHour.php');
require_once('call.php');
    class Now{
        static public function get(){
            echo json_encode(array(
                'status'=>0,
                'now'=>array(
                    "agents"=>(array(
                        "active"=>Agent::getTotalActives(),
                        "available"=>Agent::getTotalAvailable()
                    )),
                    "calls"=>(array(
                        "active"=>Call::getTotalActiveCalls(),
                        "onQueue"=>Call::getTotalCallsOnQueue()
                    )),
                    "averageTime"=>(array(
                        "handle"=>(array(
                            "time"=>TotalsHours::getAvgHandleTime() ,
                            "minutes"=>TotalsHours::getAvgHandleTimeMinutes()
                        )),
                        "wait"=>(array(
                            "time"=>TotalsHours::getAvgWaitTime(),
                            "minutes"=>TotalsHours::getAvgWaitTimeMinutes()
                        ))
                    ))
                ))
            );
        }
    }
?>