<?php
require_once('models/user.php');
//GET
  if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if($parameters == ''){
      //get all
      echo json_encode(array(
        'status'=>0,
        'users'=>json_decode(User::getAllToJson())
      ));

    }
  }
//POST
  if($_SERVER['REQUEST_METHOD'] == 'POST'){}
//PUT
  if($_SERVER['REQUEST_METHOD'] == 'PUT'){}
//DELETE
  if($_SERVER['REQUEST_METHOD'] == 'DELETE'){}
?>