<?php
    require_once('models/now.php');
    //GET
    if ($_SERVER['REQUEST_METHOD']== 'GET') {
        Now::get();
    }
?>