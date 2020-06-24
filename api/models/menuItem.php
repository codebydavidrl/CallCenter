<?php
    require_once('mysqlconection.php');
    require_once('exceptions/accessdeniendexception.php');
    require_once(__DIR__.'/../config/config.php');
    require_once(__DIR__.'/../config/security.php');
    require_once(__DIR__.'/../config/json.php');
    

    //attributes
class MenuItem{
    private $id;
    private $titleSpanish; 
    private $titleEnglish; 
    private $type; 
    private $icon; 
    private $url; 
    private $idparent; 
 

    //constructor

    public function __construct(){
        //get arguments
        $arguments=func_get_args();
        //empty eject
        if (func_num_args()==0) {
            $this->id='';
            $this->titleSpanish='';
            $this->titleEnglish='';
            $this->type='';
            $this->icon='';
            $this->url='';
            $this->idParent='';
        }

        if (func_num_args()==7) {
            $this->id=$arguments[0];
            $this->titleSpanish=$arguments[1];
            $this->titleEnglish=$arguments[2];
            $this->type=$arguments[3];
            $this->icon=$arguments[4];
            $this->url=$arguments[5];
            $this->idParent=$arguments[6];
        }
    }

    //represent the object as string
    public function toJson(){
        return json_encode(array(
            'id'=>$this->id,
            'titleSpanish'=>$this->titleSpanish,
            'titleEnglish'=>$this->titleEnglish,
            'type'=>$this->type,
            'icon'=>$this->icon,
            'url'=>$this->url,
            'idparent'=>$this->idparent,
            'children'=>Json::listToArray($this->getChildren())
        ));
    }
    
    //get menu for current user id
    public static function getUserMenu($idUser){
        $list=array();//array
        $query='select  m.id,m.titleSpanish,m.titleEnglish,m.type,m.icon,m.url,m.idParent
            from userRoles ur join roles r join menuitemsroles mr join menuItems m 
            on ur.idRole = r.id and mr.idRole = r.id and mr.idmenuitem = m.id
            where ur.idUser = ? and idParent is null
            group by m.id;';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_param('s',$idUser);
        $command->bind_result($id,$titleSpanish,$titleEnglis,$type,$icon,$url,$idparent);
        $command->execute();

        //read result
        while ($command->fetch()) {
            array_push($list,new MenuItem($id,$titleSpanish,$titleEnglis,$type,$icon,$url,$idparent));
            
        }
        mysqli_stmt_close($command);
        $connection->Close();

        return $list;//return array
    }
    
    //get roles from current user id
    private function getChildren(){
        $list=array();//array
        $query='select * from menuitems where idParent = ?;';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_param('s',$this->id);
        $command->bind_result($id,$titleSpanish,$titleEnglis,$type,$icon,$url,$idparent);
        $command->execute();

        //read result
        while ($command->fetch()) {
            array_push($list,new MenuItem($id,$titleSpanish,$titleEnglis,$type,$icon,$url,$idparent));
            
        }
        mysqli_stmt_close($command);
        $connection->Close();

        return $list;//return array
    }
 
} 
?>