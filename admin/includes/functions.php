<?php

    function getUser($conn, $id){
        $sql = "select * 
                from users as a 
                join salutations as b on a.salutation_id = b.id 
                where a.id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo $row['salutation'] . ' ' . $row['fullname'];
    }

    function getAssessor($conn, $id){
        $sql = "select * 
                from users as a 
                join salutations as b on a.salutation_id = b.id 
                where a.id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo $row['salutation'] . ' ' . $row['fullname'];
    }

    function getRole($conn, $id){
        $sql = "select * from roles where id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo $row['name'];
    }

    function getSalutation($conn, $id){
        $sql = "select * from salutations where id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo $row['salutation'];
    }

    function getCohort($conn, $id){
        $sql = "select * from cohorts where id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo $row['cohort'];
    }

    function getActivity($conn, $id){
        $sql = "select * from activities where id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo $row['activity'];
    }

    function getCase($conn, $id){
        $sql = "select * from cases where id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo $row['complexity'];
    }

    function getLevel($conn, $id){
        $sql = "select * from levels where id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        return array($row['sort'], $row['level']);
    }

    function getObservation($conn, $id){
        $sql = "select * from observations where id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo $row['observation'];
    }

    function getObservationLevel($conn, $id){
        $sql = "select * 
                from observations as a 
                join levels as b on a.level_id = b.id
                where a.id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo 'Level ' . $row['sort'] . ': ' . $row['level'];
    }


    function getSupervision($conn, $id){
        $sql = "select * from supervisions where id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        echo $row['supervision'];
    }

    /*
    function make_avatar($character){
        $path = '../../avatar' . time() . '.png';
        $image = imagecreate(200, 200);

        $red    = rand(0, 255);
        $green  = rand(0, 255);
        $blue   = rand(0, 255);

        // define background color
        imagecolorallocate($image, $red, $green, $blue);

        $textcolor = imagecolorallocate($image, 255, 255, 255);

        imagettftext($image, 100, 0, 55, 150, $textcolor, '../../font/ARIAL.ttf', $character);

        header('Content-Type: image/png');

        imagepng($image, $path);
        imagedestroy($image);

        return $path;

    }
    */

    function checkAvailability($conn, $id, $bookDate, $bookTime){
        //$id = 3;
        $bookDate = strtotime($bookDate);
        //$bookTime = '10:00';

        //echo 'Book Date: ' . $bookDate;
        $sql1 = "SELECT *
           FROM assessments 
           WHERE bookingDate = '$bookDate' AND bookingTime = '$bookTime' AND id = '$id'";
        $result1 = $conn->query($sql1);

        if ($result1->num_rows > 0){
            $ada = 1;
        } else {
            $ada = 0;
        }

        return $ada;
    }

    function getOutcome ($conn, $id){
        $sql = "select * from outcomes where id = '$id'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        //echo $row['supervision'];
        return array($row['outcome'], $row['description']);
    }

?>