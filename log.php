<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('session.save_path', '/var/lib/php/sessions');
?>

<?php
session_start();
include("admin/includes/connection.php"); //Establishing connection with our database

$error = ""; //Variable for storing our errors.

if(isset($_POST["submit"]))
{
    //print_r($_POST);
    //exit;

    if(empty($_POST["username"]) || empty($_POST["password"]))
    {
        $error = "Both fields are required.";
    }else
    {
        //print_r($_POST);
        //exit;

        // Define $username and $password
        $username=$_POST['username'];
        $password=$_POST['password'];

        // cuba untuk hashing password
        $hashed_password = md5($password);

        //To protect from MySQL injection
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        //$password = md5($password);

        //Check username and password from database
        $sql = "SELECT * 
                FROM users 
                WHERE username = '$username' AND password = '$hashed_password'";
        //$sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);

        //echo $sql;
        //exit;

        //If username and password exist in our database then create a session.
        //Otherwise echo error.

        if(mysqli_num_rows($result) > 0)
        {
            /* echo "Password1 : " . $row['password'];
            echo "<br>Password2: " . $hashed_password;


            exit; */


            $_SESSION['user_id']  = $row['id'];
            $_SESSION['studentId']  = $row['studentId'];
            $_SESSION['username'] = $username; // Initializing Session
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['email']	  = $row['email'];
            $_SESSION['role']     = $row['role_id'];
            $_SESSION['picture']  = $row['picture'];
            $_SESSION['picStatus']     = $row['picStatus'];

            // echo $_SESSION['username'];
            // echo $_SESSION['fullname'];
            // echo $_SESSION['role'];
            //exit;

            if ($_SESSION['role'] == 1){
                //echo 'disini';
                //exit;
                header("location: admin/index.php"); // Redirecting To Other Page
            } else if ($_SESSION['role'] == 2 || $_SESSION['role'] == 3){
                //echo 'disini';
                //exit;
                //header("location: judges/index.php"); // Redirecting To Other Page
                header("location: user/index.php"); // Redirecting To Other Page
            } else if ($_SESSION['role'] == 4){
                //echo 'disini';
                //exit;
                header("location: finance/index.php"); // Redirecting To Other Page
            } else if ($_SESSION['role'] == 5){
                //echo 'disini';
                //exit;
                header("location: judgetop/index.php"); // Redirecting To Other Page
            } else if ($_SESSION['role'] == 6){
                //echo 'disini';
                //exit;
                header("location: judgeaward/index.php"); // Redirecting To Other Page
            } else if ($_SESSION['role'] == 7){
                //echo 'disini';
                //exit;
                header("location: panel/index.php"); // Redirecting To Other Page
            } else {

                //header("location: user/index.php"); // Redirecting To Other Page
            }


        }else
        {
            //$error = "Incorrect username or password.";
            echo "<script type='text/javascript'>alert('Sorry! Wrong Staff ID / Password.');
                window.location='login.php';
                </script>";
            //header("location: index.php"); // Redirecting To Other Page
        }

    }
}

?>