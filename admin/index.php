<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('session.save_path', '/var/lib/php/sessions');
?>
<?php
    include("includes/check.php");
?>

<!doctype html>
<!-- 
* Bootstrap Simple Admin Template
* Version: 2.1
* Author: Alexis Luna
* Website: https://github.com/alexis-luna/bootstrap-simple-admin-template
-->
<html lang="en">

<head>
    <?php
        require_once('includes/main-header.php');
        require_once('includes/connection.php');
    ?>
</head>

<body>
    <div class="wrapper">
        <!-- sidebar --> 
        <?php
            require_once('includes/admin-sidebar.php');
        ?>
        <div id="body" class="active">
            <!-- navbar navigation component -->
            <?php
                require_once('includes/navbar.php');
            ?>
            <!-- end of navbar navigation -->
            <div class="content">
                <?php
                    if ($_SESSION['role'] == 1){
                        echo 'Ini adalah bahagian admin';
                    } else if ($_SESSION['role'] == 2){
                        echo 'ini dia';
                    } else {

                    }
                ?>
            </div>
        </div>
    </div>
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chartsjs/chart.min.js"></script>
    <script src="assets/js/dashboard-charts.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>