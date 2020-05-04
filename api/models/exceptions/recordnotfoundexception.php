<?php
    class RecordNotFoundException extends Exception{
        //attributes 
        // private $message;

        //constructor
        public function __construct(){
            if(func_get_args()==0){
                $this->message='Record Not Found';
            }
            if(func_get_args()==1){
                $this->message='Record not found for id '.$arguments[0];
            }
        }

    }
?>