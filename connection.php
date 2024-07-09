<?php
    $connection= new mysqli("localhost","root","","sh_project_latest");
    $appUrl = "http://hospital.test";
    if ($connection->connect_error){
        die("Connection failed:  ".$connection->connect_error);
    }
?>