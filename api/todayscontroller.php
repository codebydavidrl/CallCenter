<?php
    require_once('models/today.php');
    //GET
    if ($_SERVER['REQUEST_METHOD']== 'GET') {
        Today::getToday();
    }
    

?>