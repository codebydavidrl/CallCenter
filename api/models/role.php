<?php
    require_once('mysqlconection.php');
    require_once('exceptions/accessdeniendexception.php');
    require_once(__DIR__.'/../config/config.php');
    require_once(__DIR__.'/../config/security.php');
    

    //attributes
class Role{
    private $id;
    private $name; 

    //gettes and setters

    public function getId(){return $this->id;}
    public function setId($id){$this->id = $id;}

    public function getName(){return $this->name;}
    public function setName($name){$this->name = $name;} 

    //constructor

    public function __construct(){
        //get arguments
        $arguments=func_get_args();
        //empty eject
        if (func_num_args()==0) {
            $this->id='';
            $this->name='';
        }

        if (func_num_args()==2) {
            $this->id=$arguments[0];
            $this->name=$arguments[1];
        }
    }

    //represent the object as string
    public function toJson(){
        return json_encode(array(
            'id'=>$this->id,
            'name'=>$this->name,
        ));
    }
 
} 
?>