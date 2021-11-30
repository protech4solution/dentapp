<?php
    date_default_timezone_set('Asia/Kuala_Lumpur');

    // connect to database
    $dbname		= 'dentapp';
    $host		= 'localhost';
    $dbuser		= 'dbadmin';
    $dbpass		= 'db@dmin';

    // create connection
    $conn = new mysqli($host, $dbuser, $dbpass, $dbname);

    // check connection
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    } else {
        //echo 'connection ok';
    }

?>
