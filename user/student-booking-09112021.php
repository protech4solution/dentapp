<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  ini_set('session.save_path', '/var/lib/php/sessions');
?>
<?php
  include("../admin/includes/check.php");
?>

<!DOCTYPE html>
<html>
<head>
  <?php
  require_once ('includes/main-header.php');
  require_once('../admin/includes/connection.php');
  require_once('../admin/includes/functions.php');

  // update information

  // get user information
  $sq = "select *
           from users 
           where id = " . $_SESSION['user_id'];
  $rsq = $conn->query($sq);

  $rqw = $rsq->fetch_assoc();

  // process submit request
  if (isset($_POST['submitRequest'])){

    // Array ( [bookingDate] => 2021-10-07 [bookingTime] => 10:29 [activity_id] => 5 [observtion_id] => 18 [assessor_id] => 3 [submitRequest] => )

    $tDate = date('Y-m-d');
    $bDate = $_POST['bookingDate'];

    $tDate = strtotime($tDate);
    $bDate = strtotime($bDate);

    //echo 'Tarikh 1| ' . $tDate . ' --> Tarikh 2| ' . $bDate;
    echo '<br>';

    $cDate  = ($bDate - $tDate) / (60 * 60 * 24);


    if ($cDate >= 7){
      // Array ( [bookingDate] => 2021-10-11 [bookingTime] => [activity_id] => 1 [observtion_id] => 11 [assessor_id] => 3 [submitRequest] => )
      $user_id        = $_SESSION['user_id'];
      $bookingDate    = $_POST['bookingDate'];
      $bookingTime    = $_POST['bookingTime'];
      $activity_id    = $_POST['activity_id'];
      $observation_id = $_POST['observtion_id'];
      $assessor_id    = $_POST['assessor_id'];

      $bookingDate    = strtotime($bookingDate);

      $sqi1 = "select *
               from assessments 
               where user_id = '$user_id' and bookingDate = '$bookingDate' and bookingTime = '$bookingTime'";
      $rsi1 = $conn->query($sqi1);

      if ($rsi1->num_rows > 1){
        echo "<script type='text/javascript'>
                alert('Sorry! Assessment request already created.');
                window.location='student-booking.php';
            </script>";
        exit();
      } else {
        $sqi2 = "insert into assessments (user_id,
                                          assessor_id,
                                          bookingDate,
                                          bookingTime,
                                          activity_id,
                                          observation_id)
                         values ('$user_id',
                                 '$assessor_id',
                                 '$bookingDate',
                                 '$bookingTime',
                                 '$activity_id',
                                 '$observation_id')";
        echo $sqi2;
        if ($conn->query($sqi2) === TRUE){
          echo "<script type='text/javascript'>
                alert('Assessment request created successfully.');
                window.location='student-booking.php';
            </script>";
          exit();
        }
      }

      //print_r($_POST);
      //exit;
    } else {
      echo "<script type='text/javascript'>
                alert('Assessment request date must be more than a week.');
                window.location='student-booking.php';
            </script>";
      exit();
    }

    //print_r($_POST);
    exit;
  }
  ?>

  <script>
    function showObservation(str) {
      if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET","getObservation.php?q="+str,true);
        xmlhttp.send();
      }
    }
  </script>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <?php
    require_once ('includes/main-navbar.php');
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
    if ($_SESSION['role'] == 2){
      require_once ('includes/assessor-sidebar.php');
    } else if ($_SESSION['role'] == 3){
      require_once ('includes/student-sidebar.php');
    } else {}
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Request</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Assessment</li>
              <li class="breadcrumb-item active">Booking</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Activity session</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <form action="" method="post" class="form-horizontal">

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Date</label>
                  <input type="date" class="form-control" name="bookingDate">
                  <span></span>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Time</label>
                  <input type="time" class="form-control" name="bookingTime">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Activity</label>
                  <select class="form-control select2" style="width: 100%;" onchange="showObservation(this.value)" name="activity_id">
                    <option></option>
                    <?php
                      $si1 = "select *
                             from activities
                             order by activity asc";
                      $rsi1 = $conn->query($si1);
                      while ($rwi1 = $rsi1->fetch_assoc()){
                    ?>
                    <option value="<?=$rwi1['id'];?>"><?=$rwi1['activity'];?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label for="exampleInputEmail1">Observation</label>
                    <div id="txtHint"><b>Observation list...</b></div>
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Lecturer / Assessor</label>
                  <select class="form-control select2" style="width: 100%;" name="assessor_id">
                    <option></option>
                    <?php
                    $si2 = "select *
                            from users
                            where role_id = '2'
                            order by fullname asc";
                    $rsi2 = $conn->query($si2);
                    while ($rwi2 = $rsi2->fetch_assoc()){
                      ?>
                      <option value="<?=$rwi2['id'];?>"><?=getSalutation($conn, $rwi2['salutation_id']) . ' ' . ucwords($rwi2['fullname']);?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>


            <!--
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Activity</label>
                  <select class="form-control"
                          name="campus_id"
                          id="country-list"
                          onChange="getState();">
                    <option value="">  </option>
                    <?php
                    $s1 = "select * from activities order by activity asc";
                    $rs1 = $conn->query($s1);
                    while ($rw1 = $rs1->fetch_assoc()){
                      ?>
                      <option value="<?=$rw1['id'];?>"><?=ucwords($rw1['activity']);?> </option>
                      <?php
                    }
                    ?>
                  </select>
                </div>

              </div>
              <div class="col-md-6">
                <div class="form-group">

                  <div class="row">
                    <label for="exampleInputEmail1">Observation</label>
                    <select name="faculty_id"
                            id="state-list" class="form-control">
                      <option value="">Select observation</option>
                    </select>
                  </div>

                </div>
              </div>
            </div>
            -->
            <div class="row">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary" name="submitRequest">Send booking</button>
                <button type="reset" class="btn btn-secondary">Reset booking</button>
              </div>
            </div>
          </form>
        </div>
        <!-- /.card-body -->
<!--        <div class="card-footer">-->
<!--          Footer-->
<!--        </div>-->
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- /.content-wrapper -->
  <?php
    require_once ('includes/main-footer.php');
  ?>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<?php
  require_once ('includes/import-footer.php');
?>

<!-- untuk keluarkan drop down -->
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
  function getState() {
    var str='';
    var val=document.getElementById('country-list');
    for (i=0;i< val.length;i++) {
      if(val[i].selected){
        str += val[i].value + ',';
      }
    }
    var str=str.slice(0,str.length -1);

    $.ajax({
      type: "GET",
      url: "get-observation.php",
      data:'country_id='+str,
      success: function(data){
        $("#state-list").html(data);
      }
    });
  }
</script>

</body>
</html>
