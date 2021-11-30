<?php
    //include("../check.php");
    session_start();

    if (isset($_SESSION['username'])){

    } else {
        header('Location: ../login.php');
    }
    $urole = $_SESSION['role'];
    $uid 	= $_SESSION['user_id'];

    $nyear = date('Y');
    $nmonth = date('m');

    $cdate = strtotime(date('Y-m-d', time()). '00:00:00');
    
?>