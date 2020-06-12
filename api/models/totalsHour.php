<?php
    require_once('mysqlconection.php');
    require_once(__DIR__.'/../config/config.php');
    

    //attributes
class TotalsHours{
    private $id;
    private $day;
    private $hour;
    private $callsReceived;
    private $callsAnswered;
    private $callsEnded;
    private $averageWaitTime;
    private $averageHandleTime; 

    //constructor

    public function __construct(){
        //get arguments
        $arguments=func_get_args(); 
        if (func_num_args()==8) { 
            $this->id = $arguments[0];
            $this->day=$arguments[1];
            $this->hour=$arguments[2];
            $this->callsReceived= $arguments[3];
            $this->callsAnswered =$arguments[4];
            $this->callsEnded =$arguments[5];
            $this->averageWaitTime =$arguments[6];
            $this->averageHandleTime=$arguments[7];

        } 
    }

    //represent the object as string
    public function toJson(){
        return json_encode(array(
            'id'=>$this->id,
            'day'=>$this->day,
            'hour'=> $this->hour,
            'metrics'=>array(
                'callsReceived'=> $this->callsReceived,
                'callsAnswered'=> $this->callsAnswered,
                'callsEnded'=> $this->callsEnded
            ),
            'time'=>array(
                'averageWaitTime'=> $this->averageWaitTime,
                'averageHandleTime'=> $this->averageHandleTime

            )
        ));
    }

    //get all
    public static function getAll(){
        $list=array();//array
        $query='select * from totalsHour where day = curdate() order by hour asc;';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_result($id,$day,$hour,$callsReceived,$callsAnswered,$callsEnded,$averageWaitTime,$averageHandleTime);
        $command->execute();

        //read result
        while ($command->fetch()) {
            array_push($list,new TotalsHours($id,$day,$hour,$callsReceived,$callsAnswered,$callsEnded,$averageWaitTime,$averageHandleTime));
            
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

    public static function getNowInformation(){
        $connection = MySqlConnection::getConnection();//get connection
        $query = 'select * from totalsHour where day = curdate() and hour = hour(current_time());';//query
        $command = $connection->prepare($query);//prepare statement
        $command->bind_param('i',$arguments[0]);
        $command->execute();//execute
        $command->bind_result($id,$day,$hour,$callsReceived,$callsAnswered,$callsEnded,$averageWaitTime,$averageHandleTime);//bind results
        //fetch data
        if($command->fetch()) {
            //pass tha values of the fields to the attributes
             $this->id = $id;
             $this->day = $day;
             $this->hour = $hour;
             $this->callsReceived = $callsReceived;
             $this->callsAnswered = $callsAnswered;
             $this->callsEnded = $callsEnded;
             $this->averageWaitTime = $averageWaitTime;
             $this->averageHandleTime = $averageHandleTime;                    
        }
        else{
            throw new RecordNotFoundException("something went wrong");   
        }
        mysqli_stmt_close($command); //close command
        $connection->close(); //close connection

        return $this->toJson();
    } 
    //Get Average handle time
    public static function getAvgHandleTime(){
        $connection = MySqlConnection::getConnection();//get connection
        $query = 'select averageHandleTime from totalsHour as t where t.day = curdate() and t.hour = hour(current_time());';//query
        $command = $connection->prepare($query);//prepare statement 
        $command->execute();//execute
        $command->bind_result($avgHandleTime);//bind results
        //fetch data
        while($command->fetch()) {
            //pass tha values of the fields to the attributes
             $avgHandleTime = $avgHandleTime;                
        } 
        mysqli_stmt_close($command); //close command
        $connection->close(); //close connection 
        return $avgHandleTime;
    } 
    public static function getAvgHandleTimeMinutes(){
        $connection = MySqlConnection::getConnection();//get connection
        $query = 'select (time_to_sec(averageHandleTime)/60) as avgHandle from totalsHour as t where t.day = curdate() and t.hour = hour(current_time());';//query
        $command = $connection->prepare($query);//prepare statement 
        $command->execute();//execute
        $command->bind_result($avgHandleTime);//bind results
        //fetch data
        if($command->fetch()) {
            //pass tha values of the fields to the attributes
             $avgHandleTime = $avgHandleTime;                
        } 
        mysqli_stmt_close($command); //close command
        $connection->close(); //close connection 
        return round($avgHandleTime, 2);
    }  
    //Get Average wait time
    public static function getAvgWaitTime(){
        $connection = MySqlConnection::getConnection();//get connection
        $query = 'select averageWaitTime from totalsHour as t where t.day = curdate() and t.hour = hour(current_time());';//query
        $command = $connection->prepare($query);//prepare statement 
        $command->execute();//execute
        $command->bind_result($avgWaitTime);//bind results
        //fetch data
        while($command->fetch()) {
            //pass tha values of the fields to the attributes
             $avgWaitTime = $avgWaitTime;                
        } 
        mysqli_stmt_close($command); //close command
        $connection->close(); //close connection 
        return $avgWaitTime;
    } 
    public static function getAvgWaitTimeMinutes(){
        $connection = MySqlConnection::getConnection();//get connection
        $query = 'select (time_to_sec(averageWaitTime)/60) as avgHandle from totalsHour as t where t.day = curdate() and t.hour = hour(current_time());';//query
        $command = $connection->prepare($query);//prepare statement 
        $command->execute();//execute
        $command->bind_result($avgWaitTime);//bind results
        //fetch data
        if($command->fetch()) {
            //pass tha values of the fields to the attributes
             $avgWaitTime = $avgWaitTime;                
        } 
        mysqli_stmt_close($command); //close command
        $connection->close(); //close connection 
        return round($avgWaitTime, 2);
    } 
     
}

?>