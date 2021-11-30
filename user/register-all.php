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

    // accept asessment
    if (isset($_POST['btnAccept'])){
      $id = $_POST['id'];

      $sql1 = "update assessments
               set request_status = '1'
               where id = '$id'";
      if ($conn->query($sql1) === TRUE){
        echo "<script type='text/javascript'>
                  alert('Assessment save successfully.');
                  window.location='register-all.php';
              </script>";
        exit();
      } else {
        print_r($_POST);
        exit;
      }

    }

    // accept asessment
    if (isset($_POST['btnReject'])){
      $id = $_POST['id'];

      $sql1 = "update assessments
               set request_status = '9'
               where id = '$id'";
      if ($conn->query($sql1) === TRUE){
        echo "<script type='text/javascript'>
                  alert('Assessment save successfully.');
                  window.location='register-all.php';
              </script>";
        exit();
      } else {
        print_r($_POST);
        exit;
      }
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

  <?php
    $n = 0;
    if (isset($_GET['n'])){
      $n = $_GET['n'];
    }
  ?>
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
            <h1>All Request</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Request</li>
              <li class="breadcrumb-item active">All</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if ($n == 0) {
      ?>
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">List of all assessment request</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th></th>
              <th>DATE</th>
              <th>TIME</th>
              <th>ACTIVITY</th>
              <th>OBSERVATION</th>
              <th>STUDENT NAME</th>
              <th>STATUS</th>
            </tr>
            </thead>
            <tbody>
            <?php
              $sqi1 = "select *
                       from assessments
                       where assessor_id = '" . $_SESSION['user_id'] . "' and status = '1'";
              $rsi1 = $conn->query($sqi1);

              $i = 0;
              while ($rwi1 = $rsi1->fetch_assoc()){
                $reqStatus = '';
                $btnStatus = '';
                $icon = '';

                if ($rwi1['request_status'] == 1){
                  $reqStatus = 'Accepted';
                  $btnStatus = 'btn-success';
                  $icon = 'fa fa-check';
                } if ($rwi1['request_status'] == 2){
                  $reqStatus = 'Pending';
                  $btnStatus = 'btn-warning';
                  $icon = 'fa fa-edit';
                } if ($rwi1['request_status'] == 9){
                  $reqStatus = 'Reject';
                  $btnStatus = 'btn-danger';
                  $icon = 'fa fa-ban';
                } else {

                }
            ?>
            <tr>
              <td><?=++$i;?></td>
              <td style="overflow:hidden; white-space:nowrap"><?=date('d-m-Y', $rwi1['bookingDate']);?></td>
              <td><?=$rwi1['bookingTime'];?></td>
              <td><?=getActivity($conn, $rwi1['activity_id']);?></td>
              <td><?=getObservation($conn, $rwi1['observation_id']);?></td>
              <td style="min-width: 30%;"><?=getUser($conn, $rwi1['user_id']);?></td>
              <td style="width: 150px;">
                <button class="btn <?=$btnStatus;?>" style="" title="<?=$reqStatus;?>"><i class="<?=$icon;?>" style="padding-left: 5px;"></i></button>
                <a href="?n=1&id=<?=$rwi1['id'];?>" class="btn btn-primary" title="View detail"><i class="fa fa-binoculars"></i></a>
              </td>
            </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
              <th></th>
              <th>DATE</th>
              <th>TIME</th>
              <th>ACTIVITY</th>
              <th>OBSERVATION</th>
              <th>STUDENT NAME</th>
              <th>STATUS</th>
            </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
<!--        <div class="card-footer">-->
<!--          Footer-->
<!--        </div>-->
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->
      <?php
        } else if ($n == 1){
      ?>
      <!-- view assessment information -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Assessment request information</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <form action="" method="POST">
          <table id="example11" class="table table-bordered table-striped">
            <thead>
            </thead>
            <tbody>
            <?php
            $sqi1 = "select *
                   from assessments
                   where id = '" . $_GET['id'] . "'";
            $rsi1 = $conn->query($sqi1);

            $i = 0;
            $rwi1 = $rsi1->fetch_assoc();
              $reqStatus = '';
              $btnStatus = '';
              $icon = '';

              if ($rwi1['request_status'] == '1'){
                $reqStatus = '<span style="color:#9CC862;">ACCEPTED</span>';
                $btnStatus = 'btn-success';
                $icon = 'fa fa-check';
              } else if ($rwi1['request_status'] == '2') {
                $reqStatus = '<span style="color:#FFC107;">PENDING FOR APPROVAL</span>';
                $btnStatus = 'btn-warning';
                $icon = 'fa fa-edit';
              } else if ($rwi1['request_status'] == '9'){
                $reqStatus = '<span style="color:#FF1900;">REJECT</span>';
                $btnStatus = 'btn-danger';
                $icon = 'fa fa-ban';
              } else {

              }
              ?>
              <tr>
                <td>Date</td>
                <td>
                  <?=date('d-m-Y', $rwi1['bookingDate']);?>
                  <input type="hidden" class="form-control" name="id" value="<?=$rwi1['id'];?>">
                </td>
              </tr>
              <tr>
                <td>Time</td>
                <td><?=$rwi1['bookingTime'];?></td>
              </tr>
              <tr>
                <td>Activity</td>
                <td><?=getActivity($conn, $rwi1['activity_id']);?></td>
              </tr>
              <tr>
                <td>Observation</td>
                <td><?=getObservation($conn, $rwi1['observation_id']);?></td>
              </tr>
              <tr>
                <td>Requestor name</td>
                <td><?=getUser($conn, $rwi1['user_id']);?></td>
              </tr>
              <tr>
                <td>Request status</td>
                <td><b><?=$reqStatus;?></b></td>
              </tr>
            <?php  ?>
            </tbody>
          </table>

          <?php
            if ($rwi1['request_status'] == 2){
          ?>
          <button class="btn btn-success" name="btnAccept" type="submit">ACCEPT</button>
          <button class="btn btn-warning" name="btnReject" type="submit">REJECT</button>
          <a href="?n=0" class="btn btn-secondary">CANCEL</a>
          <?php
            } else {
          ?>
          <a href="?n=0" class="btn btn-secondary">CANCEL</a>
          <?php
            }
          ?>

          </form>
        </div>
        <!-- /.card-body -->
        <!--        <div class="card-footer">-->
        <!--          Footer-->
        <!--        </div>-->
        <!-- /.card-footer-->
      </div>
      <!-- close view assessment information-->
      <?php
        } else {}
      ?>

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



</body>
</html>
