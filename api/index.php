<?php
  //Headers
  header('Access-Control-Allow-Origin:*');
  //Allow methods
  header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE');
  //Request url
  $requestURL = substr($_SERVER['REQUEST_URI'],strlen(dirname($_SERVER['PHP_SELF'])));
  //split url in parts
  $urlParts = explode('/',$requestURL); 
  //validate url

  if(sizeof($urlParts) == 3 || sizeof($urlParts) == 4){
    //controller
    $controller = $urlParts[1];
    //action     
    if(sizeof($urlParts) == 4){
      $action=$urlParts[2];
      $parameters = $urlParts[3];
    }else{
      $action='';
      $parameters = $urlParts[2];
    } 
    //Go to controler
    $controller.='controller.php';
    if(file_exists($controller)){
      require_once($controller);
    }else{
      echo json_encode(array("status"=>"998", "errorMessage"=>"Invalid Controller" ));
    }


  }else{
    echo json_encode(array("status"=>"999", "errorMessage"=>"Invalid URL" ));
  }


  
?>