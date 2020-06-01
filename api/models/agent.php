<?php
    //require files
    require_once('mysqlconection.php');
    require_once(__DIR__.'/../config/config.php');
    require_once('exceptions/recordnotfoundexception.php');
    class Agent{
        private $id;
        private $name;
        private $photo;
        private $pin;
    
        //gettes and setters
    
        public function getId(){return $this->id;}
        public function setId($id){$this->id = $id;}
    
        public function getName(){return $this->name;}
        public function setName($name){$this->name = $name;}
    
        public function getPhoto(){return $this->photo;}
        public function setPhoto($photo){$this->photo = $photo;}
    
        
        public function setPin($pin){$this->pin = $pin;}
    
        //constructor
    
        public function __construct(){
            //get arguments
            $arguments=func_get_args();
            //empty eject
            if (func_num_args()==0) {
                $this->id='';
                $this->name='';
                $this->photo='';
                $this->pin='';
    
            }
            //1 arguments : gets data from the data base
            if (func_num_args() == 1) {
                $connection = MySqlConnection::getConnection();//get connection
                $query = 'select id, name, photo, pin from agents where id = ?';//query
                $command = $connection->prepare($query);//prepare statement
                $command->bind_param('i',$arguments[0]);
                $command->execute();//execute
                $command->bind_result($id,  $name, $photo,$pin);//bind results
                //fetch data
                if($command->fetch()) {
                    //pass tha values of the fields to the attributes
                    $this->id = $id;
                    $this->name = $name;
                    $this->photo = $photo;//add item to list
                    $this->pin = $pin;
                }
                else{
                    throw new RecordNotFoundException($arguments[0]);
                    $this->id = '';
                    $this->name = '';
                    $this->photo = '';
                    $this->pin = '';
                }
                mysqli_stmt_close($command); //close command
                $connection->close(); //close connection
            }
    
            if (func_num_args()==4) {
                $this->id=$arguments[0];
                $this->name=$arguments[1];
                $this->photo=$arguments[2];
                $this->pin=$arguments[3];
                
    
            }
    
    
    
        }
    
        //represent the object as string
        public function toJson(){
            return json_encode(array(
                'id'=>$this->id,
                'name'=>$this->name,
                'photo'=> Config::getFileUrl('userPhotos').$this->photo,
                'pin'=>sha1($this->pin)
            ));
        }
    
        //get all
        public static function getAll(){
            $list=array();//array
            $query='select id,name,photo,pin from agents order by id';//query
            $connection= MySqlConnection::getConnection();
            $command=$connection->prepare($query);
            $command->bind_result($id,$name,$photo,$pin);
            $command->execute();
    
            //read result
            while ($command->fetch()) {
                array_push($list,new Agent($id,$name,$photo,$pin));
                
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

        public static function getActiveAgentsByHour(){
            $data = array();
            $now = new DateTime();
            $hourNow= $now->format('H');
            $hours = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
            foreach ($hours as $hour) { 
                // if hour query is later than now
                if($hour <=  $hourNow ){
                    $connection = MySqlConnection::getConnection();//get connection
                    $query = 'select count(*) from sessions where date(startdatetime) = curdate() and hour(startdatetime) <= ? ;';//query
                    $command = $connection->prepare($query);//prepare statement
                    $command->bind_param('i',$hour);
                    $command->execute();//execute
                    $command->bind_result($result);//bind results
                    //fetch data
                    if($command->fetch())
                        //result found 
                        array_push($data,doubleval($result));
                }else
                //not result
                array_push($data,0); 
            } 

            return $data;
        }

        public static function getActiveAgents(){
            $data = array();
            $connection = MySqlConnection::getConnection();//get connection
            $query = 'select a.id,a.name,a.photo,a.pin from sessions s join agents a on s.idAgent = a.id where date(startdatetime) = curdate()';//query
            $command = $connection->prepare($query);//prepare statement 
            $command->execute();//execute
            $command->bind_result($id,$name,$photo,$pin);//bind results
            //fetch data
            while($command->fetch()){
                //result found 
                array_push($data,new Agent($id,$name,$photo,$pin));
            }

            return $data;
        }
        public static function getActiveAgentsToJson() {
            $jsonArray = array(); //create JSON array
            //reaqd items
            foreach(self::getActiveAgents() as $item) {
                array_push($jsonArray, json_decode($item->toJson()));
            }
            return json_encode($jsonArray); // return JSON array
        }
        public static function getAvailableAgents(){
            $data = array();
            $connection = MySqlConnection::getConnection();//get connection
            $query = 'select a.id,a.name,a.photo,a.pin from sessions s join agents a on s.idAgent = a.id where date(startdatetime) = curdate() and s.status=1';//query
            $command = $connection->prepare($query);//prepare statement 
            $command->execute();//execute
            $command->bind_result($id,$name,$photo,$pin);//bind results
            //fetch data
            while($command->fetch())
                //result found 
                array_push($data,new Agent($id,$name,$photo,$pin));

            return $data;
        }
        public static function getAvailableAgentsToJson() {
            $jsonArray = array(); //create JSON array
            //reaqd items
            foreach(self::getAvailableAgents() as $item) {
                array_push($jsonArray, json_decode($item->toJson()));
            }
            return json_encode($jsonArray); // return JSON array
        }
    }
?>