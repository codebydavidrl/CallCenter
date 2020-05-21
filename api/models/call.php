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
    }
?>