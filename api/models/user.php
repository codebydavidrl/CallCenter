<?php
    require_once('mysqlconection.php');
    require_once('exceptions/accessdeniendexception.php');
    require_once(__DIR__.'/../config/config.php');
    require_once(__DIR__.'/../config/security.php');
    require_once(__DIR__.'/../config/json.php');
    require_once('role.php');
    require_once('menuItem.php');

    //attributes
class User{
    private $id;
    private $name;
    private $photo;
    private $password;
    private $theme;
    private $language; 

    //gettes and setters

    public function getId(){return $this->id;}
    public function setId($id){$this->id = $id;}

    public function getName(){return $this->name;}
    public function setName($name){$this->name = $name;}

    public function getPhoto(){return $this->photo;}
    public function setPhoto($photo){$this->photo = $photo;}
    
    public function setPassword($password){$this->password = $password;}
    
    public function getTheme(){return $this->theme;}
    public function setTheme($theme){$this->theme = $theme;}

    public function getLanguage(){return $this->language;}
    public function setLanguage($language){$this->language = $language;}

    

    //constructor

    public function __construct(){
        //get arguments
        $arguments=func_get_args();
        //empty eject
        if (func_num_args()==0) {
            $this->id='';
            $this->name='';
            $this->photo='';
            $this->password='';
            $this->theme='light';
            $this->language='sp';
        }
        if (func_num_args()==1) { 
            $query='select id,name,photo,theme,language from users where id =?';//query
            $connection= MySqlConnection::getConnection();
            $command=$connection->prepare($query);
            $command->bind_param('s',$arguments[0]);
            $command->bind_result($id,$name,$photo,$theme,$language);
            $command->execute();

            //read result
            if ($command->fetch()) {
                $this->id=$id;
                $this->name=$name;
                $this->photo=$photo;
                $this->theme=$theme;
                $this->language=$language; 

            }
            else 
             throw new AccessDeniedException($arguments[0]);
            mysqli_stmt_close($command);
            $connection->Close(); 
        }
        if (func_num_args()==2) {
            $this->name=$arguments[0];
            $this->password=$arguments[1];  
            $query='select id,name,photo,theme,language from users where id =? and password = sha(?)';//query
            $connection= MySqlConnection::getConnection();
            $command=$connection->prepare($query);
            $command->bind_param('ss',$arguments[0],$arguments[1]);
            $command->bind_result($id,$name,$photo,$theme,$language);
            $command->execute();

            //read result
            if ($command->fetch()) {
                $this->id=$id;
                $this->name=$name;
                $this->photo=$photo;
                $this->theme=$theme;
                $this->language=$language; 

            }
            else 
             throw new AccessDeniedException($arguments[0]);
            mysqli_stmt_close($command);
            $connection->Close(); 

        }

        if (func_num_args()==5) {
            $this->id=$arguments[0];
            $this->name=$arguments[1];
            $this->photo=$arguments[2];
            $this->theme=$arguments[3];
            $this->language=$arguments[4]; 
        }
    }

    //represent the object as string
    public function toJson(){
        return json_encode(array(
            'id'=>$this->id,
            'name'=>$this->name,
            'photo'=> Config::getFileUrl('userPhotos').$this->photo,
            'theme'=>$this->theme,
            'language'=>$this->language,
            'menu'=>Json::listToArray(MenuItem::getUserMenu($this->id)),
            'roles'=>Json::listToArray($this->getRoles()),
            'token'=>Security::generateToken($this->id)
        ));
    }

    //get all
    public static function getAll(){
        $list=array();//array
        $query='select id,name,photo , theme ,language from users order by name';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_result($id,$name,$photo ,$theme , $language);
        $command->execute();

        //read result
        while ($command->fetch()) {
            array_push($list,new User($id,$name,$photo,$theme , $language));
            
        }
        mysqli_stmt_close($command);
        $connection->Close();

        return $list;//return array
    }
    //get roles from current user id
    private function getRoles(){
        $list=array();//array
        $query='select r.id,r.name from userRoles ur join roles r on ur.idRole = r.id where ur.idUser = ?';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_param('s',$this->id);
        $command->bind_result($id,$name);
        $command->execute();

        //read result
        while ($command->fetch()) {
            array_push($list,new Role($id,$name));
            
        }
        mysqli_stmt_close($command);
        $connection->Close();

        return $list;//return array
    }

    //check if user belongs to a role
    public static function belongsToRole($userName,$roles){  
        $isWorthy=false;
        $query='select idRole from userroles where idUser = ?;';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_param('s',$userName);
        $command->bind_result($idRole);
        $command->execute();
        
        //read result
        while ($command->fetch()) {
            foreach ($roles as $role) {
                if($role == $idRole){
                    $isWorthy=true;
                }
            }
        
        } 
        mysqli_stmt_close($command);
        $connection->Close(); 
        return $isWorthy;
    }
     

 
} 
?>