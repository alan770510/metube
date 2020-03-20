<?php

    ob_start();
    session_start();
    date_default_timezone_set("America/New_York");
    try{
        $con = new PDO("mysql:host=localhost:3308;dbname=youtube","root","");
//        https://www.php.net/manual/en/pdo.setattribute.php
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    catch(PDOException $e){
        echo "Connection Failed: ". $e->getMessage();
    }
?>