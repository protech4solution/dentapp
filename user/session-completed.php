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

    $currDate = date('d-m-Y');
    $currDate = strtotime($currDate);
    $currTime = date("H:i:sa");

    // update information

    // get user information
    $sq = "select *
             from users 
             where id = " . $_SESSION['user_id'];
    $rsq = $conn->query($sq);
    $rqw = $rsq->fetch_assoc();

    // process submit request
    if (isset($_POST['btnSubmit'])){
      /*Array ( [id] => 3
                [domain1] => 2
                [domain2] => 3
                [domain3] => 2
                [domain4] => 1
                [domain5] => 2
                [epa_id] => 2
                [outcome] => Met expectation
                [duration] => 30
                [btnSubmit] => ) */
      $id       = $_POST['id'];
      $domain1  = $_POST['domain1'];
      $domain2  = $_POST['domain2'];
      $domain3  = $_POST['domain3'];
      $domain4  = $_POST['domain4'];
      $domain5  = $_POST['domain5'];
      $epa_id   = $_POST['epa_id'];
      $outcome  = $_POST['outcome'];
      $duration = $_POST['duration'];
      $assessor_id = $_SESSION['user_id'];

      // save assessing
      $sql1 = "INSERT INTO assessment_assessors (assessment_id,
                                                 assessor_id,
                                                 domain1,
                                                 domain2,
                                                 domain3,
                                                 domain4,
                                                 domain5,
                                                 epa_id,
                                                 outcome,
                                                 duration)
                            VALUES('$id',
                                   '$assessor_id',
                                   '$domain1',
                                   '$domain2',
                                   '$domain3',
                                   '$domain4',
                                   '$domain5',
                                   '$epa_id',
                                   '$outcome',
                                   '$duration')";
      if ($conn->query($sql1) === TRUE){
        // update assessment status
        $sql2 = "UPDATE assessments
                 SET status = '1'
                 WHERE id = '$id'";
        if ($conn->query($sql2) === TRUE){
          echo "<script type='text/javascript'>
                  alert('Data save successfully.');
                  window.location='session-incoming.php';
              </script>";
          exit();
        }

      }

      print_r($_POST);
      exit;
    }

    // submit feedback
    if (isset($_POST['btnSubmitFeedback'])){
      $id = $_POST['id'];
      $feedback1   = mysqli_real_escape_string($conn,$_POST['feedback1']);
      $feedback2   = mysqli_real_escape_string($conn,$_POST['feedback2']);
      $feedback3   = mysqli_real_escape_string($conn,$_POST['feedback3']);
      $feedback4   = mysqli_real_escape_string($conn,$_POST['feedback4']);
      $feedback5   = mysqli_real_escape_string($conn,$_POST['feedback5']);
      $assessor_id = $_SESSION['user_id'];

      $sql1 = "INSERT INTO assessment_feedbacks(assessor_id,
                                                assessment_id,
                                                feedback1,
                                                feedback2,
                                                feedback3,
                                                feedback4,
                                                feedback5)
                            VALUES ('$assessor_id',
                                    '$id',
                                    '$feedback1',
                                    '$feedback2',
                                    '$feedback3',
                                    '$feedback4',
                                    '$feedback5')";

      //echo $sql1;
      //exit;

      if ($conn->query($sql1) === TRUE){
        $last_id = $conn->insert_id;
        // update assessment status
        $sql2 = "UPDATE assessments
                 SET feedback_id = '$last_id'
                 WHERE id = '$id'";
        if ($conn->query($sql2) === TRUE){
          echo "<script type='text/javascript'>
                  alert('Feedback save successfully.');
                  window.location='session-completed.php';
              </script>";
          exit();
        }

      }

      print_r($_POST);
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

  <style>
    .saperator {
      padding: 1px !important;
      vertical-align: top;
      border-top: 1px solid #dee2e6;
    }
  </style>
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
            <h1>Completed Session</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Session</li>
              <li class="breadcrumb-item active">Completed</li>
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
          <h3 class="card-title">List of all completed assessment session</h3>

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
                       where assessor_id = '" . $_SESSION['user_id'] . "' and request_status = '1' and status = '1'";
              $rsi1 = $conn->query($sqi1);

              $i = 0;
              while ($rwi1 = $rsi1->fetch_assoc()){
                $reqStatus = '';
                $btnStatus = '';
                $icon = '';

                if ($rwi1['status'] == 1){
                  $reqStatus = 'Completed';
                  $btnStatus = 'btn-success';
                  $icon = 'fa fa-check';
                } if ($rwi1['status'] == 2){
                  $reqStatus = 'Pending';
                  $btnStatus = 'btn-warning';
                  $icon = 'fa fa-edit';
                } if ($rwi1['status'] == 9){
                  $reqStatus = 'Reject';
                  $btnStatus = 'btn-danger';
                  $icon = 'fa fa-ban';
                } else {

                }

              if ($rwi1['feedback_id'] > 0){
                $feedback = 'Sending feedback';
                $feedbackStatus = 'btn-success';
                $feedbackIcon = 'fa fa-comment';
              } else {
                $feedback = 'Waiting for feedback';
                $feedbackStatus = 'btn-warning';
                $feedbackIcon = 'fa fa-comment';
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
                <a href="?n=1&id=<?=$rwi1['id'];?>" class="btn <?=$btnStatus;?>" title="<?=$reqStatus;?>"><i class="fa <?=$icon;?>"></i></a>
                <a href="?n=2&id=<?=$rwi1['id'];?>" class="btn <?=$feedbackStatus;?>" title="<?=$feedback;?>"><i class="fa <?=$feedbackIcon;?>"></i></a>
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
          <h1 class="card-title">Competency Assessment Analytic Rubric </h1>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <script src="https://cdn.tiny.cloud/1/hd75qnyp7pt3rd5yw7e8dqfvfa7496wmrzhlpi0mv9stwfxr/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
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
                <td>Entrustment level for the concerned activity</td>
                <td><?=getObservationLevel($conn, $rwi1['observation_id']);?></td>
              </tr>
              <tr>
                <td>Student name</td>
                <td><?=getUser($conn, $rwi1['user_id']);?></td>
              </tr>
            <?php  ?>
            </tbody>
          </table>

          <!-- ------------------- evaluation part ------------------ -->
          <h5 for="">Assessment rubrics</h5>
          <table id="example11" class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
              <tr>
                <th style="vertical-align: middle;">
                  Domains<br>
                  <i>(check the appropriate domain(s) to a particular activity)</i>
                </th>
                <th style="width: 15%;vertical-align: middle;">
                  Below expectation<br>
                  <i>[Meets some performance criteria, but performs at a lower level than expected]</i>
                </th>
                <th style="width: 15%; vertical-align: middle;">
                  Met expectation<br>
                  <i>[Meets all performance criteria]</i>
                </th>
                <th style="width: 15%;vertical-align: middle;">
                  Exceeded expectation<br>
                  <i>[Performance exceeds expectations; demonstrated meritorious performance significantly above the average dental student]</i>
                </th>
              </tr>
            </thead>
            <tbody>
              <!-- Domain 1 -->
              <tr>
                <td style="vertical-align: middle;"><b>Knowledge and understanding </b><br>
                  [Acquire and apply the knowledge of basic clinical and dental sciences to ensure effective chair-side assisting and patient management]
                  <br><a href="" data-toggle="modal" data-target=".domain1">Domain details</a>
                </td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain1" required value="1"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain1" value="2"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain1" value="3"></td>
              </tr>
              <tr>
                <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                </td>
              </tr>
              <!-- domain 2 -->
              <tr>
                <td style="vertical-align: middle;"><b>Cognitive skills/Critical thinking, problem-solving</b><br>
                  [Utilize critical thinking and problem solving skills in patient care decision making]
                  <br><a href="" data-toggle="modal" data-target=".domain2">Domain details</a>
                </td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain2" required value="1"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain2" value="2"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain2" value="3"></td>
              </tr>
              <tr>
                <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                </td>
              </tr>

              <!-- domain 3 -->
              <tr>
                <td style="vertical-align: middle;"><b>Functional Work Skills/ Practical skills </b><br>
                  [Demonstrate skills in patient management and clinical care]
                  <br><a href="" data-toggle="modal" data-target=".domain3">Domain details</a>
                </td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain3" required value="1"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain3" value="2"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain3" value="3"></td>
              </tr>
              <tr>
                <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                </td>
              </tr>

              <!-- domain 4 -->
              <tr>
                <td style="vertical-align: middle;"><b>Functional Work Skills/Communication and interpersonal skill</b><br>
                  [Communicate effectively with peers in the dental and other health professions, patients and community]
                  <br><a href="" data-toggle="modal" data-target=".domain4">Domain details</a>
                </td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain4" required value="1"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain4" value="2"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain4" value="3"></td>
              </tr>
              <tr>
                <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                </td>
              </tr>

              <!-- domain 5 -->
              <tr>
                <td style="vertical-align: middle;"><b>Ethics and Professionalism</b>
                  <br>
                  [Adhere to the legal, ethical principles and the professional code of conduct in patient care]
                  <br><a href="" data-toggle="modal" data-target=".domain5">Domain details</a>
                </td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain5" required value="1"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain5" value="2"></td>
                <td style="vertical-align: middle;"><input type="radio" class="form-control" name="domain5" value="3"></td>
              </tr>
              <tr>
                <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                </td>
              </tr>
            </tbody>
          </table>

          <div class="row">
            <div class="col-md-12">
              <h5>Indicate the level of supervision required (for the next case)</h5>
              Based on my observation(s), I suggest for this EPA(observable patient encounter) the student may be ready after the next review to be:
              <br><br>
              <?php
              // display epas
              $sql4 = "select *
                     from levels
                     order by id asc";
              $result4 = $conn->query($sql4);
              while ($row4 = $result4->fetch_assoc()){
                ?>
                <div class="input-group mb-3 col-md-12" style="margin-left: -0.6em;">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <input type="radio" aria-label="Checkbox for following text input" name="epa_id" value="<?=$row4['id'];?>">
                    </div>
                  </div>
                  <input type="text" class="form-control" aria-label="Text input with checkbox" value="<?=$row4['level'];?>" disabled>
                </div>
              <?php } ?>
            </div>
          </div>


          <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <h5 for="">Outcome</h5>
                    <select name="outcome" id="" class="form-control">
                      <option value=""></option>
                      <option value="Below expectation">Below expectation</option>
                      <option value="Met expectation">Met expectation</option>
                      <option value="Exceed expectation">Exceed expectation</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <h5 for="">Duration of observation</h5>
                    <input type="number" class="form-control" placeholder="minutes" name="duration">
                  </div>
                </div>
              </div>

          <button class="btn btn-success" name="btnSubmit" type="submit">SAVE ASSESSMENT</button>
          <a href="?n=0" class="btn btn-secondary">CANCEL</a>


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
        } else if ($n == 2){
      ?>
          <!-- view assessment information -->
          <div class="card">
            <div class="card-header">
              <h1 class="card-title">Competency Assessment Analytic Rubric </h1>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fas fa-times"></i></button>
              </div>
            </div>
            <div class="card-body">
              <script src="https://cdn.tiny.cloud/1/hd75qnyp7pt3rd5yw7e8dqfvfa7496wmrzhlpi0mv9stwfxr/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
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

                  // get assessment info
                  $sqi2 = "select *
                           from assessment_assessors
                           where assessment_id = '" . $_GET['id'] . "'";
                  $rsi2 = $conn->query($sqi2);
                  $rwi2 = $rsi2->fetch_assoc();

                  //print_r($rwi2);

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
                    <td>Entrustment level for the concerned activity</td>
                    <td><?=getObservationLevel($conn, $rwi1['observation_id']);?></td>
                  </tr>
                  <tr>
                    <td>Student name</td>
                    <td><?=getUser($conn, $rwi1['user_id']);?></td>
                  </tr>
                  <?php  ?>
                  </tbody>
                </table>

                <!-- ------------------- evaluation part ------------------ -->
                <h5 for="">Assessment rubrics</h5>
                <table id="example11" class="table table-bordered table-striped table-hover">
                  <thead class="thead-dark">
                  <tr>
                    <th style="vertical-align: middle;">
                      Domains<br>
                      <i>(check the appropriate domain(s) to a particular activity)</i>
                    </th>
                    <th>Outcome</th>
                  </tr>
                  </thead>
                  <tbody>
                  <!-- Domain 1 -->
                  <tr>
                    <td style="vertical-align: middle;"><b>Knowledge and understanding </b><br>
                      [Acquire and apply the knowledge of basic clinical and dental sciences to ensure effective chair-side assisting and patient management]
                      <br><a href="" data-toggle="modal" data-target=".domain1">Domain details</a>
                    </td>
                    <td style="vertical-align: middle;">
                      <?php
                        $domain1 = getOutcome($conn, $rwi2['domain1']);
                        echo $domain1[0];
                        echo '<br>[' . $domain1[1] . ']';
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                    </td>
                  </tr>
                  <!-- domain 2 -->
                  <tr>
                    <td style="vertical-align: middle;"><b>Cognitive skills/Critical thinking, problem-solving</b><br>
                      [Utilize critical thinking and problem solving skills in patient care decision making]
                      <br><a href="" data-toggle="modal" data-target=".domain2">Domain details</a>
                    </td>
                    <td style="vertical-align: middle;">
                      <?php
                        $domain2 = getOutcome($conn, $rwi2['domain2']);
                        echo $domain2[0];
                        echo '<br>[' . $domain2[1] . ']';
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                    </td>
                  </tr>

                  <!-- domain 3 -->
                  <tr>
                    <td style="vertical-align: middle;"><b>Functional Work Skills/ Practical skills </b><br>
                      [Demonstrate skills in patient management and clinical care]
                      <br><a href="" data-toggle="modal" data-target=".domain3">Domain details</a>
                    </td>
                    <td style="vertical-align: middle;">
                      <?php
                        $domain3 = getOutcome($conn, $rwi2['domain3']);
                        echo $domain3[0];
                        echo '<br>[' . $domain3[1] . ']';
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                    </td>
                  </tr>

                  <!-- domain 4 -->
                  <tr>
                    <td style="vertical-align: middle;"><b>Functional Work Skills/Communication and interpersonal skill</b><br>
                      [Communicate effectively with peers in the dental and other health professions, patients and community]
                      <br><a href="" data-toggle="modal" data-target=".domain4">Domain details</a>
                    </td>
                    <td style="vertical-align: middle;">
                      <?php
                        $domain4 = getOutcome($conn, $rwi2['domain4']);
                        echo $domain4[0];
                        echo '<br>[' . $domain4[1] . ']';
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                    </td>
                  </tr>

                  <!-- domain 5 -->
                  <tr>
                    <td style="vertical-align: middle;"><b>Ethics and Professionalism</b>
                      <br>
                      [Adhere to the legal, ethical principles and the professional code of conduct in patient care]
                      <br><a href="" data-toggle="modal" data-target=".domain5">Domain details</a>
                    </td>
                    <td style="vertical-align: middle;">
                      <?php
                        $domain5 = getOutcome($conn, $rwi2['domain5']);
                        echo $domain5[0];
                        echo '<br>[' . $domain5[1] . ']';
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" style="background-color: black; height: 1px;" class="saperator">
                    </td>
                  </tr>
                  </tbody>
                </table>

                <div class="row">
                  <div class="col-md-12">
                    <h5>Indicate the level of supervision required (for the next case)</h5>
                    Based on my observation(s), I suggest for this EPA(observable patient encounter) the student may be ready after the next review to be:
                    <br><br>
                    <?php
                    // display epas
                    $sql4 = "select *
                             from levels
                             where id = '" . $rwi2['epa_id'] . "'
                             order by id asc";
                    $result4 = $conn->query($sql4);
                    while ($row4 = $result4->fetch_assoc()){
                      ?>
                      <div class="input-group mb-3 col-md-12" style="margin-left: -0.6em;">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <input type="radio" aria-label="Checkbox for following text input" name="epa_id" value="<?=$row4['id'];?>" checked>
                          </div>
                        </div>
                        <input type="text" class="form-control" aria-label="Text input with checkbox" value="<?=$row4['level'];?>" disabled>
                      </div>
                    <?php } ?>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <h5 for="">Outcome overall</h5>
                      <label for="">
                        <?php
                        $overalOutcome = getOutcome($conn, $rwi2['epa_id']);
                        echo $overalOutcome[0];
                        echo '<br>[' . $overalOutcome[1] . ']';
                        ?>
                      </label>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <h5 for="">Duration of observation</h5>
                      <label for=""><?=$rwi2['duration'];?> minutes</label>
                    </div>
                  </div>
                </div>

                <hr>

                <?php
                  if ($rwi1['feedback_id'] > 0){
                    $sql3 = "SELECT *
                             FROM assessment_feedbacks
                             WHERE id = '" . $rwi1['feedback_id'] . "'";
                    //echo $sql3;
                    $rst3 = $conn->query($sql3);
                    $rwi3 = $rst3->fetch_assoc();
                ?>
                <h5>Assessor feedback</h5>
                Provide feedback on the performance (correspondence to competency domains relevant to his EPA; strengths; weaknesses and how can the student improve)
                <br><br>
                <table id="example11" class="table table-bordered table-striped table-hover">
                  <thead class="thead-dark">
                  <tr>
                    <th style="width: 15%;">Criteria</th>
                    <th>Feedback</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <th>Knowledge & Understanding</th>
                    <td><i><?=$rwi3['feedback1'];?></i></td>
                  </tr>
                  <tr>
                    <th>Cognitive Skills ??? Critical thinking/problem solving</th>
                    <td><i><?=$rwi3['feedback2'];?></i></td>
                  </tr><tr>
                    <th>Functional Work Skills ??? Practical skills</th>
                    <td><i><?=$rwi3['feedback3'];?></i></td>
                  </tr><tr>
                    <th>Functional Work Skills ??? Communication skills</th>
                    <td><i><?=$rwi3['feedback4'];?></i></td>
                  </tr>
                  <tr>
                    <th>Ethics and Professionalism</th>
                    <td><i><?=$rwi3['feedback5'];?></i></td>
                  </tr>
                  </tbody>
                </table>

                <?php } else { ?>
                <h5>Assessor feedback</h5>
                Provide feedback on the performance (correspondence to competency domains relevant to his EPA; strengths; weaknesses and how can the student improve)
                <br><br>
                <table id="example11" class="table table-bordered table-striped table-hover">
                  <thead class="thead-dark">
                  <tr>
                    <th style="width: 15%;">Criteria</th>
                    <th>Feedback</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <th>Knowledge & Understanding</th>
                    <td>
                      <textarea name="feedback1" id="" rows="2" class="form-control"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <th>Cognitive Skills ??? Critical thinking/problem solving</th>
                    <td>
                      <textarea name="feedback2" id="" rows="2" class="form-control"></textarea>
                    </td>
                  </tr><tr>
                    <th>Functional Work Skills ??? Practical skills</th>
                    <td>
                      <textarea name="feedback3" id="" rows="2" class="form-control"></textarea>
                    </td>
                  </tr><tr>
                    <th>Functional Work Skills ??? Communication skills</th>
                    <td>
                      <textarea name="feedback4" id="" rows="2" class="form-control"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <th>Ethics and Professionalism</th>
                    <td>
                      <textarea name="feedback5" id="" rows="2" class="form-control"></textarea>
                    </td>
                  </tr>
                  </tbody>
                </table>
                <button class="btn btn-success" name="btnSubmitFeedback" type="submit">SENT FEEDBACK</button>
                <?php } ?>

                <?php if ($rwi1['reflection_id'] < 1){ ?>

                <?php
                  } else {
                    $sql5 = "SELECT *
                             FROM assessment_reflections
                             WHERE assessment_id = '" . $rwi1['id'] . "'";
                    $rst5 = $conn->query($sql5);
                    $rwi5 = $rst5->fetch_assoc();
                ?>
                  <hr>
                  <h5>Student reflection</h5>
                  <div class="row">
                    <div class="col-md-6">
                      <b>Reflection on dental aspects</b><br>
                      <i><?=$rwi5['dentalAspect'];?></i>
                    </div>
                    <div class="col-md-6">
                      <b>Reflection on professionalism</b><br>
                      <i><?=$rwi5['professionalism'];?></i>
                    </div>
                  </div>
                  <br>
                  <hr>
                <?php } ?>
                <a href="?n=0" class="btn btn-secondary">CANCEL</a>


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
    <!-- untuk paparkan modal -->
    <!-- domain 1 -->
    <div class="modal fade domain1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Area of assessment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <table class="table table-bordered">
              <tbody>
              <tr>
                <td>Demonstrates effective information gathering through history taking. </td>
              </tr>
              <tr>
                <td>Demonstrates effective information gathering through extraoral and intraoral examining skills.</td>
              </tr>
              <tr>
                <td>Prescribes necessary investigations and interprets their findings.</td>
              </tr>
              <tr>
                <td>Seeks secondary sources of information when appropriate (family dentist, specialist from another dental discipline, physician).</td>
              </tr>
              <tr>
                <td>Integrates history taking, clinical examination and investigation results to prioritize the working differential diagnosis.</td>
              </tr>
              <tr>
                <td>Diagnoses hard and soft tissue pathologies and conditions.</td>
              </tr>
              <tr>
                <td>Provides treatment options and formulates appropriate treatment plan with due consideration to medical history.</td>
              </tr>
              <tr>
                <td>Recognizes the need for urgent or immediate treatment and initiates appropriate course of action.</td>
              </tr>
              <tr>
                <td>Knows personal limitation of knowledge and considers referral when appropriate</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- domain 2 -->
    <div class="modal fade domain2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Area of assessment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
              <tbody>
              <tr>
                <td>Exhibits intellectual inquisitiveness to generate, discover or restructure ideas or to be able   to imagine alternatives patient care decision making (customizes care for patient).</td>
              </tr>
              <tr>
                <td>Demonstrates capacity to adapt, accommodate, modify, or change thoughts, ideas, and behaviour in patient care.</td>
              </tr>
              <tr>
                <td>Exhibits eagerness to learn by seeking knowledge and understanding through observation and thoughtful questioning in order to explore possibilities and alternatives in patient management.</td>
              </tr>
              <tr>
                <td>Recognizes the pursuit of learning and determination to overcome obstacles, clinical challenges or difficulty.</td>
              </tr>
              <tr>
                <td>Contemplates assumptions, thinking and acts for the purpose of deeper understanding and self-evaluation and seeks timely assistance when unexpected event or complication occurs.</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- domain 3 -->
    <div class="modal fade domain3" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Area of assessment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
              <tbody>
              <tr>
                <td>Effectively explains the disease process, and its management as well as possible complications to the patient/guardian and obtains inform consent.</td>
              </tr>
              <tr>
                <td>Dons appropriate PPE for the planned procedure.</td>
              </tr>
              <tr>
                <td>Demonstrates appropriate chairside infection control procedures and safe use of instruments and sharps.</td>
              </tr>
              <tr>
                <td>Performs the clinical procedure in line with current best practice.</td>
              </tr>
              <tr>
                <td>Uses appropriate dental materials and dental armamentarium for the procedure.</td>
              </tr>
              <tr>
                <td>Demonstrates proper disposal of clinical wastes and sharps.</td>
              </tr>
              <tr>
                <td>Prescribes drugs complete with patient details, prescriber details, clinical diagnosis and appropriately written prescription.</td>
              </tr>
              <tr>
                <td>Knows personal limitation of skills and seeks assistance when appropriate.</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- domain 4 -->
    <div class="modal fade domain4" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Area of assessment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td>Demonstrates empathy towards the patient and their caregivers through verbal or non-verbal dialogue.</td>
                </tr>
                <tr>
                  <td>Communicates clearly and respectfully with the team (patients, caregivers, peers, assistants, and auxillaries) throughout the procedure (initial interviewing skills or during and after the procedure). </td>
                </tr>
                <tr>
                  <td>Explains and discusses the following clearly with patients and care givers: (1) diagnosis and treatment plan; (2) benefits, risks, and discomforts related to treatments; (3) relevant preventive health strategies; (4) post-operative instructions and care; (5) sensitive   and payment options.</td>
                </tr>
                <tr>
                  <td>Allows patients to express their need and acknowledges their concerns.</td>
                </tr>
                <tr>
                  <td>Customizes treatment care according to patient???s need without compromising quality of care to the patient.</td>
                </tr>
                <tr>
                  <td>Forms a good rapport with the patient/caregiver through effective verbal and non-verbal cues and establishes trust.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- domain 5 -->
    <div class="modal fade domain5" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Area of assessment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
              <tbody>
              <tr>
                <td>Completes required documentation in timely manner and maintains accurate clinical records.</td>
              </tr>
              <tr>
                <td>Respects patient???s privacy and confidentiality always.</td>
              </tr>
              <tr>
                <td>Places patients??? interests first and acts to protect them.</td>
              </tr>
              <tr>
                <td>Respects patients??? dignity and choices in all interactions and shows a commitment to equality and diversity.</td>
              </tr>
              <tr>
                <td>Demonstrates integrity, respect, compassion within team, actively and respectfully involves all members of the health care team to enhance patient care.</td>
              </tr>
              <tr>
                <td>Demonstrates a commitment to lifelong learning, shows an aptitude for self-motivated learning, strives to stay updated in current knowledge, technology, and literature.</td>
              </tr>
              <tr>
                <td>Recognizes and acts within the regulatory councils??? standards and other professionally relevant laws, complies with current best practice guidance to ensure delivery of high-quality service to the patient.</td>
              </tr>
              <tr>
                <td>Seeks feedback from peers, supervisors, dental surgery assistants or technicians when necessary.</td>
              </tr>
              <tr>
                <td>Demonstrates satisfactory degree of confidence concurrent to the level of training.</td>
              </tr>
              <tr>
                <td>Obtains inform consent and ensures patient reckon all the treatment options (pros and cons, cost, risk, and complication) prior to treatments.</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

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
