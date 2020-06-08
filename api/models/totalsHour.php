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
}

?>