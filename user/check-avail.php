<?php
  require_once('../admin/includes/connection.php');
  require_once('../admin/includes/functions.php');

  $id = 3;
  $bookDate = $bookDate = strtotime('2021-11-29');
  $bookTime = '10:00';

  echo 'Book Date: ' . $bookDate;
  $sql1 = "SELECT *
           FROM assessments 
           WHERE bookingDate = '$bookDate' AND bookingTime = '$bookTime' AND id = '$id'";
  $result1 = $conn->query($sql1);

  if ($result1->num_rows > 0){
    echo '1';
  } else {
    echo '0';
  }


?>
