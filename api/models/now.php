<?php
    class Now{
        static public function get(){
            echo json_encode(array(
                'status'=>0,
                'now'=>json_decode(json_encode(array(
                    "agents"=>json_decode(json_encode(array(
                        "active"=>0,
                        "available"=>0
                    ))),
                    "calls"=>json_decode(json_encode(array(
                        "active"=>0,
                        "onQueue"=>0
                    ))),
                    "averageTime"=>json_decode(json_encode(array(
                        "handle"=>json_decode(json_encode(array(
                            "time"=>"00:00:00",
                            "minutes"=>0.0
                        ))),
                        "wait"=>json_decode(json_encode(array(
                            "time"=>"00:00:00",
                            "minutes"=>0.0
                        )))
                    )))
                ))))
            );
        }
    }
?>