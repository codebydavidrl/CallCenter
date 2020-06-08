<?php
    require_once('mysqlconection.php');
    require_once('workstation.php');
    require_once('agent.php');
    class Session{
        //attributes
        private $id;
        private $agent ;
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

        public function getStatusDescription(){}

        //constructor
        public function __construct(){
            //get arguments
            $arguments=func_get_args();
            //Empty one
            if (func_num_args()== 0) { 
                $this->id='';
                $this->agent='';
                $this->workstation='';
                $this->startDateTime='';
                $this->endDateTime='';
                $this->status='';
                $this->availableSince='';   
            }
            if(func_num_args() == 1){
                $connection = MySqlConnection::getConnection();//get connection
                $query = 'select id,idAgent,idWorkstation,startdatetime,enddatetime,status,availableSince from sessions where id= ?;';//query
                $command = $connection->prepare($query);//prepare statement
                $command->bind_param('i',$arguments[0]);
                $command->execute();//execute
                $command->bind_result($id,$agent,$workstation,$startDateTime,$endDateTime,$status,$availableSince);//bind results
                //fetch data
                if($command->fetch()) {
                    //pass tha values of the fields to the attributes
                     $this->id = $id;
                     $this->agent = $agent;
                     $this->workstation = $workstation;
                     $this->startDateTime = $startDateTime;
                     $this->endDateTime = $endDateTime;
                     $this->status = $status;
                     $this->availableSince = $availableSince;                    
                }
                else{
                    throw new RecordNotFoundException($arguments[0]);   
                }
                mysqli_stmt_close($command); //close command
                $connection->close(); //close connection
            }
            //start session
            if (func_num_args()== 7) { 
                $this->id=$arguments[0];
                $this->agent=$arguments[1];
                $this->workstation=$arguments[2];
                $this->startDateTime=$arguments[3];
                $this->endDateTime=$arguments[4];
                $this->status=$arguments[5];
                $this->availableSince=$arguments[6];   
            }
        }
        //instance methods
        //represent the object in json format
        public function toJson(){
            if((isset($this->agent) && isset($this->workstation)) && ($this->agent != '' &&  $this->workstation != '')){
                $agent = new Agent($this->agent);
                $workstation = new Workstation($this->workstation);
            }else{
                $agent = new Agent();
                $workstation = new Workstation();
            }

            return json_encode(array(
                "id"=>$this->id,
                "agent"=>json_decode($agent->toJson()),
                "workstation"=>json_decode($workstation->toJson()),
                "dateTime"=>array(
                    "startDateTime"=>$this->startDateTime,
                    "endDateTime"=>$this->endDateTime,
                    "availableSince"=>$this->availableSince
                ),
                "status"=>array(
                    "status"=>$this->status,
                    "description"=>$this->getStatusDescription()
                )
            ));
        }


        //class methods
        public static function getAll(){
            $list=array();//array
            $query='select id,idAgent,idWorkstation,startdatetime,enddatetime,status,availableSince from sessions';//query
            $connection= MySqlConnection::getConnection();
            $command=$connection->prepare($query);
            $command->bind_result($id,$agent,$workstation,$startDateTime,$endDateTime,$status,$availableSince);
            $command->execute();

            //read result
            while ($command->fetch()) {
                array_push($list,new Session($id,$agent,$workstation,$startDateTime,$endDateTime,$status,$availableSince));
                
            }
            mysqli_stmt_close($command);
            $connection->Close();

            return $list;//return array
        } 

        public static function getActive(){
            $list=array();//array
            $query='select id from sessions where status <> 4;';//query
            $connection= MySqlConnection::getConnection();
            $command=$connection->prepare($query);
            $command->bind_result($id);
            $command->execute();

            //read result
            while ($command->fetch()) {
                array_push($list,new Session($id));
                
            }
            mysqli_stmt_close($command);
            $connection->Close();

            return $list;//return array
        
        }
        public static function getAvailable(){
            $list=array();//array
            $query='select id from sessions where status =1;';//query
            $connection= MySqlConnection::getConnection();
            $command=$connection->prepare($query);
            $command->bind_result($id);
            $command->execute();

            //read result
            while ($command->fetch()) {
                array_push($list,new Session($id));
            }
            mysqli_stmt_close($command);
            $connection->Close();

            return $list;//return array
        
        }
        
        //start session
        public static function start($agentid,$pin,$workstationid){
             //results
             $results=array(
                0=>'Session started',
                1=>'Invalid user pin',
                2=>'Has already another session',
                3=>'Invalid workstation',
                4=>'Workstation already in use by another session',
                999=>'Could not start session'
            );
            //procedire result
            $procedureResult = 999;
            //execute tored procedure
            $connection = MySqlConnection:: getConnection();
            //connection open 
            if($connection){
                //query
                $query = 'call spStartSession('.$agentid.','.$pin.','.$workstationid.',@result);select @result;';
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
        //end session
        public static function end($id){

        }

        //end call
        public static function endCall($idSession){
            //results 
            $results=array(
                0=>'Call Ended',
                1=>'another call Answered || id session invalid',
                2=>'another call Added to Queue',
                999=>'Could not end calls'
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