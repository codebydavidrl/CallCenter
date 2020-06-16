<?php
    class AccessDeniedException extends Exception{
        protected $message;
        public function __contruct($id){
            $this->message = 'Access denied for'.$id;
        }
    }
?>