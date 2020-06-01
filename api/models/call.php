<?php
    require_once('mysqlconection.php');
    require_once(__DIR__.'/../config/config.php');
    require_once('exceptions/recordnotfoundexception.php');
    require_once('session.php');

    
class Call{
    //attributes
    private $id;
    private $phone;
    private $status;
    private $session;
    private $receivedDateTime;
    private $answeredDateTime;
    private $endDateTime;
    private $waitTime;
    private $handleTime;
    //getter and setters 
    public function getId(){ return $this->id;}

    public function getPhone(){return $this->phone;}
	public function setPhone($phone){$this->phone = $phone;}

	public function getStatus(){return $this->status;}
	public function setStatus($status){$this->status = $status;}

	public function getSession(){return $this->session;}
	public function setSession($session){$this->session = $session;}

	public function getReceivedDateTime(){return $this->receivedDateTime;}
	public function setReceivedDateTime($receivedDateTime){$this->receivedDateTime = $receivedDateTime;}

	public function getAnsweredDateTime(){return $this->answeredDateTime;}
	public function setAnsweredDateTime($answeredDateTime){$this->answeredDateTime = $answeredDateTime;}

	public function getEndDateTime(){return $this->endDateTime;}
	public function setEndDateTime($endDateTime){$this->endDateTime = $endDateTime;}

	public function getWaitTime(){return $this->waitTime;}
	public function setWaitTime($waitTime){$this->waitTime = $waitTime;}

	public function getHandleTime(){return $this->handleTime;}
	public function setHandleTime($handleTime){$this->handleTime = $handleTime;}
    //constructor
    public function __construct(){
        //get arguments
        $arguments=func_get_args();
        //empty eject
        if (func_num_args()==0) {
            $this->id='';
            $this->phone='';
            $this->status='';
            $this->session='';
            $this->receivedDateTime='';
            $this->answeredDateTime='';
            $this->endDateTime='';
            $this->waitTime='';
            $this->handleTime='';
        }  
        if(func_num_args() == 1){
            $connection = MySqlConnection::getConnection();//get connection
            $query = 'select id,phoneNumber,status,idSession,receiveddatetime,answereddatetime,enddatetime,waitTime,handleTime from calls where date(receiveddatetime) = curdate() and id=?';//query
            $command = $connection->prepare($query);//prepare statement
            $command->bind_param('i',$arguments[0]);
            $command->execute();//execute
            $command->bind_result($id,$phone,$status,$session,$receivedDateTime,$answerDateTime,$endDateTime,$waitTime,$handleTime);//bind results
            //fetch data
            if($command->fetch()) {
                //pass tha values of the fields to the attributes
                $this->id=$id;
                $this->phone=$phone;
                $this->status=$status;
                $this->session=$session;
                $this->receivedDateTime=$receivedDateTime;
                $this->answeredDateTime=$answerDateTime;
                $this->endDateTime=$endDateTime;
                $this->waitTime=$waitTime;
                $this->handleTime=$handleTime;
            }
            else
                throw new RecordNotFoundException($arguments[0]);
            mysqli_stmt_close($command); //close command
            $connection->close(); //close connection
        }

        if (func_num_args()==9) {
            $this->id=$arguments[0];
            $this->phone=$arguments[1];
            $this->status=$arguments[2];
            $this->session=$arguments[3];
            $this->receivedDateTime=$arguments[4];
            $this->answeredDateTime=$arguments[5];
            $this->endDateTime=$arguments[6];
            $this->waitTime=$arguments[7];
            $this->handleTime=$arguments[8];
        }  
    }
    //instance methods
    //represent the object as string
    public function toJson(){
        $session = new Session($this->session);
            return json_encode(array(
                'id'=>$this->id,
                'phone'=>$this->phone,
                'status'=>$this->status,
                'session'=>json_decode($session->toJson()),
                'receivedDateTime'=>$this->receivedDateTime,
                'answeredDateTime'=>$this->answeredDateTime,
                'endDateTime'=>$this->endDateTime,
                'waitTime'=>$this->waitTime,
                'handleTime'=>$this->handleTime

            ));
    }
    //class methods
    //receive call
    public static function receive($phoneNumber){
            //results
            $results=array(
                0=>'Called Received',
                1=>'Call Answered',
                2=>'Call Added to Queue',
                999=>'Could not add call to queue'
            );
            //procedire result
            $procedureResult = 999;
            //execute tored procedure
            $connection = MySqlConnection:: getConnection();
            //connection open 
            if($connection){
                //query
                $query = 'call spReceiveCall('.$phoneNumber.',@result);select @result;';
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
    public static function getCallsByHour(){
            $data=array();//array
            $hours = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
            foreach ($hours as $hour) {
                $connection = MySqlConnection::getConnection();//get connection
                $query = 'select callsReceived from totalshour where day = curdate() and hour = ?';//query
                $command = $connection->prepare($query);//prepare statement
                $command->bind_param('i',$hour);
                $command->execute();//execute
                $command->bind_result($result);//bind results
                //fetch data
                if($command->fetch())
                    //result found
                    array_push($data,$result);
                else
                    //not result
                    array_push($data,0);
            }
            return $data;
    }
    public static function getAverageHandleTimeByHour(){
            $data=array();//array
            $hours = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
            foreach ($hours as $hour) {
                $connection = MySqlConnection::getConnection();//get connection
                $query = 'select (time_to_sec(averageHandleTime)/60) from totalshour where day = curdate() and hour = ?;';//query
                $command = $connection->prepare($query);//prepare statement
                $command->bind_param('i',$hour);
                $command->execute();//execute
                $command->bind_result($result);//bind results
                //fetch data
                if($command->fetch())
                    //result found 
                    array_push($data,doubleval($result));
                else
                    //not result
                    array_push($data,0);
            }
            return $data;
    }
    public static function getAverageWaitTimeByHour(){
            $data=array();//array
            $hours = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
            foreach ($hours as $hour) {
                $connection = MySqlConnection::getConnection();//get connection
                $query = 'select (time_to_sec(averageWaitTime)/60) from totalshour where day = curdate() and hour = ?;';//query
                $command = $connection->prepare($query);//prepare statement
                $command->bind_param('i',$hour);
                $command->execute();//execute
                $command->bind_result($result);//bind results
                //fetch data
                if($command->fetch())
                    //result found 
                    array_push($data,doubleval($result));
                else
                    //not result
                    array_push($data,0);
            }
            return $data;
    }
    public static function getAllToday(){
        $list=array();//array
        $query='select id,phoneNumber,status,idSession,receiveddatetime,answereddatetime,
        enddatetime,waitTime,handleTime from calls where date(receiveddatetime) = curdate();';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_result($id,$phone,$status,$session,$receivedDateTime,$answerDateTime,$endDateTime,$waitTime,$handleTime);
        $command->execute();

        //read result
        while ($command->fetch()) {
            array_push($list,new Call($id,$phone,$status,$session,$receivedDateTime,$answerDateTime,$endDateTime,$waitTime,$handleTime));
            
        }
        mysqli_stmt_close($command);
        $connection->Close();

        return $list;//return array
    }

    public static function getAllTodayToJson() {
        $jsonArray = array(); //create JSON array
        //reaqd items
        foreach(self::getAllToday() as $item) {
            array_push($jsonArray, json_decode($item->toJson()));
        }
        return json_encode($jsonArray); // return JSON array
    }
    public static function getAll(){
        $list=array();//array
        $query='select id,phoneNumber,status,idSession,receiveddatetime,answereddatetime,
        enddatetime,waitTime,handleTime from calls';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_result($id,$phone,$status,$session,$receivedDateTime,$answerDateTime,$endDateTime,$waitTime,$handleTime);
        $command->execute();

        //read result
        while ($command->fetch()) {
            array_push($list,new Call($id,$phone,$status,$session,$receivedDateTime,$answerDateTime,$endDateTime,$waitTime,$handleTime));
            
        }
        mysqli_stmt_close($command);
        $connection->Close();

        return $list;//return array
    }

    public static function getAllToJson() {
        $jsonArray = array(); //create JSON array
        //reaqd items
        foreach(self::getAll() as $item) {
            array_push($jsonArray, json_decode($item->toJson()));
        }
        return json_encode($jsonArray); // return JSON array
    }
}
?>