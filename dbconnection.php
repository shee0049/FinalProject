<?php


    $dbConn = new PDO('mysql:host=localhost; dbname=CST8257', 'PHPSCRIPT', '1234'
                ,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ,PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);



define('DB_SERVER', 'localhost');

define('DB_USERNAME', 'PHPSCRIPT');

define('DB_PASSWORD', '1234');

define('DB_NAME', 'CST8257');

?>



