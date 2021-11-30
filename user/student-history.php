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

  $n = 0;
  if (isset($_GET['n'])){
    $n = $_GET['n'];
  } else {
    $n = 0;
  }

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

  <?php
    // submit reflection
    if (isset($_POST['btnSubmitReflection'])){
      $user_id     = $_SESSION['user_id'];
      $id          = $_POST['id'];
      $dentalAspect= mysqli_real_escape_string($conn,$_POST['dentalAspect']);
      $professionalism = mysqli_real_escape_string($conn,$_POST['professionalism']);

      $sql1 = "INSERT INTO assessment_reflections (user_id,
                                                   assessment_id,
                                                   dentalAspect,
                                                   professionalism)
                           VALUES ('$user_id',
                                   '$id',
                                   '$dentalAspect',
                                   '$professionalism')";
      if ($conn->query($sql1) === TRUE){
        $last_id = $conn->insert_id;
        // update assessment status
        $sql2 = "UPDATE assessments
                 SET reflection_id = '$last_id'
                 WHERE id = '$id'";
        if ($conn->query($sql2) === TRUE){
          echo "<script type='text/javascript'>
                  alert('Reflection save successfully.');
                  window.location='student-history.php';
              </script>";
          exit();
        }

      }

      print_r($_POST);
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
            <h1>History</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Assessment</li>
              <li class="breadcrumb-item active">History</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <?php if ($n == 0){ ?>
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
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th></th>
              <th>DATE & TIME</th>
              <th>ACTIVITY</th>
              <th>OBSERVATION</th>
              <th>ASSESSOR NAME</th>
              <th>STATUS</th>
            </tr>
            </thead>
            <tbody>
            <?php
              $sqi1 = "select *
                       from assessments
                       where user_id = '" . $_SESSION['user_id'] . "'";
              $rsi1 = $conn->query($sqi1);

              $i = 0;
              while ($rwi1 = $rsi1->fetch_assoc()) {
                $reqStatus = '';
                $btnStatus = '';

                $assStatus = 0;
                if (($rwi1['request_status'] == 1) AND ($rwi1['status'] == 1)){
                  $assStatus = 1;
                  $reqStatus = 'COMPLETED';
                  $btnStatus = 'btn-success';
                } else if ($rwi1['request_status'] == 1){
                  $reqStatus = 'ACCEPTED';
                  $btnStatus = 'btn-primary';
                } else if ($rwi1['request_status'] == 2){
                  $reqStatus = 'WAITING';
                  $btnStatus = 'btn-danger';
                } else if ($rwi1['request_status'] == 9){
                  $reqStatus = 'REJECTED';
                  $btnStatus = 'btn-warning';
                } else {

                }
            ?>
            <tr>
              <td><?=++$i;?></td>
              <td><?=date('d.m.Y', $rwi1['bookingDate']) . '<br>' . $rwi1['bookingTime'];?></td>
              <td><?=getActivity($conn, $rwi1['activity_id']);?></td>
              <td><?=getObservation($conn, $rwi1['observation_id']);?></td>
              <td style="min-width: 30%;"><?=getAssessor($conn, $rwi1['assessor_id']);?></td>
              <td style="width: 150px;">
                <?php if ($assStatus == 1){ ?>
                  <a href="?n=2&id=<?=$rwi1['id'];?>" class="btn btn-success" style="width: 100%;"><?=$reqStatus;?></a>
                <?php } else { ?>
                  <button class="btn <?=$btnStatus;?>" style="width: 100%;"><?=$reqStatus;?></button>
                <?php } ?>

              </td>
            </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
              <th></th>
              <th>DATE & TIME</th>
              <th>ACTIVITY</th>
              <th>OBSERVATION</th>
              <th>ASSESSOR NAME</th>
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
      <?php } else if ($n == 1){ ?>

      <?php } else if ($n == 2){ ?>
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
                  <td>Assessor name</td>
                  <td><?=getUser($conn, $rwi1['assessor_id']);?></td>
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
                    <th>Cognitive Skills – Critical thinking/problem solving</th>
                    <td><i><?=$rwi3['feedback2'];?></i></td>
                  </tr><tr>
                    <th>Functional Work Skills – Practical skills</th>
                    <td><i><?=$rwi3['feedback3'];?></i></td>
                  </tr><tr>
                    <th>Functional Work Skills – Communication skills</th>
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
                    <th>Cognitive Skills – Critical thinking/problem solving</th>
                    <td>
                      <textarea name="feedback2" id="" rows="2" class="form-control"></textarea>
                    </td>
                  </tr><tr>
                    <th>Functional Work Skills – Practical skills</th>
                    <td>
                      <textarea name="feedback3" id="" rows="2" class="form-control"></textarea>
                    </td>
                  </tr><tr>
                    <th>Functional Work Skills – Communication skills</th>
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

              <?php if ($rwi1['reflection_id'] > 0){ ?>

              <?php } else { ?>
                <hr>
                <h5>Student reflection</h5>
                <div class="row">
                  <div class="col-md-6 form-group">
                    <label for="">Reflection on dental aspects</label>
                    <textarea name="dentalAspect" id="" rows="4" class="form-control"></textarea>
                  </div>

                  <div class="col-md-6 form-group">
                    <label for="">Reflection on professionalism</label>
                    <textarea name="professionalism" id="" rows="4" class="form-control"></textarea>
                  </div>
                </div>

                <button class="btn btn-success" name="btnSubmitReflection" type="submit">SUBMIT REFLECTION</button>
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
      <?php } else {} ?>

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
