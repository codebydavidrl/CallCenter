<?php
    require_once('mysqlconection.php');
    require_once(__DIR__.'/../config/config.php');
    

    //attributes
class WorkStation{
    private $id;
    private $description;
    private $ipAddress;
    

    //gettes and setters

    public function getId(){return $this->id;}
    public function setId($id){$this->id = $id;}

    public function getDescription(){return $this->description;}
    public function setDescription($description){$this->description = $description;}

    public function getIpAddress(){return $this->ipAddress;}
    public function setIpAddress($ipAddress){$this->ipAddress = $ipAddress;}

    
   
    //constructor

    public function __construct(){
        //get arguments
        $arguments=func_get_args();
        //empty eject
        if (func_num_args()==0) {
            $this->id='';
            $this->description='';
            $this->ipAddress='';
            

        }
        if (func_num_args() == 1) {
            $connection = MySqlConnection::getConnection();//get connection
            $query = 'select id, description,ipAddress from workstations where id = ?';//query
            $command = $connection->prepare($query);//prepare statement
            $command->bind_param('i',$arguments[0]);
            $command->execute();//execute
            $command->bind_result($id, $description,$ipAddress);//bind results
            //fetch data
            if($command->fetch()) {
                //pass tha values of the fields to the attributes
                $this->id = $id;
                $this->description = $description;
                $this->ipAddress = $ipAddress;
                
            }
            else{
                throw new RecordNotFoundException($arguments[0]);
                $this->id = '';
                $this->description = '';
                $this->ipAddress = '';
            }
            mysqli_stmt_close($command); //close command
            $connection->close(); //close connection
        }

        if (func_num_args()==3) {
            $this->id=$arguments[0];
            $this->description=$arguments[1];
            $this->ipAddress=$arguments[2];
            
        }



    }

    //represent the object as string
    public function toJson(){
        return json_encode(array(
            'id'=>$this->id,
            'description'=>$this->description,
            'ipAddress'=> $this->ipAddress

        ));
    }

    //get all
    public static function getAll(){
        $list=array();//array
        $query='select id,description,ipAddress from workstations';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_result($id,$description,$ipAddress);
        $command->execute();

        //read result
        while ($command->fetch()) {
            array_push($list,new WorkStation($id,$description,$ipAddress));
            
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