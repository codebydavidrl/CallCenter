<?php
    require_once('mysqlconection.php');
    
    class Call{
        //attributes
        //getter and setters
        //constructor
        //instance methods
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
    }
?>