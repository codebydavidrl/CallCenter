<?php
    require_once('mysqlconection.php');
    class Session{
        //attributes
        private $id;
        private $agent;
        private $workstation;
        private $startDateTime;
        private $endDateTime;
        private $status;
        private $availableSince;



        //getter and setters
        public function getId(){return $this->id;}
        public function getAgent(){return $this->agent;}
        public function getWorkStation(){return $this->workstation;}
        public function getStartDateTime(){return $this->startDateTime;}
        public function getEndDateTime(){return $this->endDateTime;}
        public function getStatus(){return $this->status;}
        public function getAvailableSince(){return $this->availableSince;}

        //constructor
        public function __construct(){
            //get arguments
            $arguments=func_get_args();

            //start session
            if (func_num_args()==3) {
                # code...
                $agentid=$arguments[0];
                $pin=$arguments[1];
                $workstationid[2];
            }
        }
        //instance methods
        //represent the object in json format
        public function tojson(){

        }


        //class methods
        public static function getActiveSessions(){
            
        }
        public static function getActiveSessionsToJson(){
            
        }

        //start session
        public static function start($agentid,$pin,$workstationid){

        }




        //end call
        public static function endcall($idSession){
            //results
            $results=array(
                0=>'Call Ended',
                1=>'there arent calls',
                999=>'Could not End call'
            );
            //procedire result
            $procedureResult = 999;
            //execute tored procedure
            $connection = MySqlConnection:: getConnection();
            //connection open 
            if($connection){
                //query
                $query = 'call spEndCall('.$idSession.',@result); select @result;';
                //execute
                $dataSet = $connection->multi_query($query);
                //check if there are results
                if($dataSet){
                    //loop thru result tables
                    do{
                        //get result
                        if($result = $connection->store_result()){
                            //loop thru rows
                            while($row = $result->fetch_row()){
                                //loop thru fields
                                foreach($row as $field) $procedureResult = $field;
                            }
                        }
                    }while($connection->next_result());
                    //close connection
                    $connection->close();
                    //return result
                    return json_encode(array(
                        'status' => $procedureResult,
                        'message' => $results[$procedureResult]
                    ));
                }
            }
        }
    }
?>