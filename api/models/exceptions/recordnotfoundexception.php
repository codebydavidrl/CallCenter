<?php
    class RecordNotFoundException extends Exception {
        
        
        //constructor
        public function _construct() {
            //get arguments 
            $arguments = func_get_args();
            //0 arguments : generic message 
            if(func_num_args() == 0)
                $this->message = 'Record not found';
            //argument specific mesage 
            if(func_num_args() ==1)
                $this->message = 'Record not found for id' .$arguments[0];

        }
    }
?>