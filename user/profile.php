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
    if (isset($_POST['updateInfo'])){
      //Array ( [cohort_id] => 2 [salutation_id] => 6 [fullname] => AUDREY GAN MEI XIN [studentId] => 00000023725 [updateInfo] => )
      $cohort_id      = $_POST['cohort_id'];
      $salutation_id  = $_POST['salutation_id'];
      $fullname       = $_POST['fullname'];
      $studentId      = $_POST['studentId'];

      $sql = "update users
              set cohort_id = '$cohort_id',
                  salutation_id = '$salutation_id',
                  fullname = '$fullname',
                  studentId = '$studentId'
              where id = '" . $_SESSION['user_id'] . "'";
      //echo $sql;
      //exit;
      if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>
                  alert('Data update save successfully.');
                  window.location='profile.php';
              </script>";
        exit();
      }
    }

    // change password
    if (isset($_POST['changePassword'])){
      // Array ( [oldpassword] => admin [newpassword1] => student [newpassword2] => student [changePassword] => )

      $oldpassword  = $_POST['oldpassword'];
      $newpassword1 = $_POST['newpassword1'];
      $newpassword2 = $_POST['newpassword2'];

      if ($newpassword1 != $newpassword2){
        echo "<script type='text/javascript'>
                  alert('Sorry! Please enter same new password.');
                  window.location='profile.php';
              </script>";
        exit();
      } else {
        $newps = md5($newpassword1);
        $sql = "update users
                set password = '$newps'
                where id = '" . $_SESSION['user_id'] . "'";
        //echo $sql;
        //exit;
        if ($conn->query($sql) === TRUE) {
          echo "<script type='text/javascript'>
                  alert('Data update save successfully.');
                  window.location='profile.php';
              </script>";
          exit();
        }
      }

//      print_r($_POST);
//      exit;
    }

    // get user information
    $sq = "select *
           from users 
           where id = " . $_SESSION['user_id'];
    $rsq = $conn->query($sq);

    $rqw = $rsq->fetch_assoc();
  ?>
</head>
<body class="hold-transition sidebar-mini">
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
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="../avatar/<?=$_SESSION['picture'];?>"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"><?=$_SESSION['fullname'];?></h3>

                <p class="text-muted text-center"><?=getRole($conn, $_SESSION['role']);?></p>

                <!--
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Followers</b> <a class="float-right">1,322</a>
                  </li>
                  <li class="list-group-item">
                    <b>Following</b> <a class="float-right">543</a>
                  </li>
                  <li class="list-group-item">
                    <b>Friends</b> <a class="float-right">13,287</a>
                  </li>
                </ul>
                -->
                <a href="#" class="btn btn-primary btn-block"><b>Update Picture</b></a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <!--
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Education</strong>

                <p class="text-muted">
                  B.S. in Computer Science from the University of Tennessee at Knoxville
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                <p class="text-muted">Malibu, California</p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                <p class="text-muted">
                  <span class="tag tag-danger">UI Design</span>
                  <span class="tag tag-success">Coding</span>
                  <span class="tag tag-info">Javascript</span>
                  <span class="tag tag-warning">PHP</span>
                  <span class="tag tag-primary">Node.js</span>
                </p>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
              </div>
            </div>
            -->
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Personal information</a></li>
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Change password</a></li>
                  <!--
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">User Logs</a></li>
                  -->

                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <!-- ---------------- Panel 1 ---------------- -->
                  <div class="active tab-pane" id="activity">
                    <form action="" method="post">
                      <?php
                        if ($_SESSION['role'] == '3') {
                          $cohortDisplay = '';
                        } else {
                          $cohortDisplay = 'style="display: none;"';
                        }
                      ?>
                      <div class="row form-group" <?=$cohortDisplay;?>>
                            <div class="col-md-2">
                              <label for="">Cohort</label>
                              <select name="cohort_id" id="" class="form-control">
                                <option value="<?=$rqw['cohort_id'];?>"><?=getCohort($conn, $rqw['cohort_id']);?></option>
                                <?php
                                $sql1 = "select * 
                                     from cohorts
                                     where id != " . $rqw['cohort_id'] . "
                                     order by cohort asc";
                                $result1 = $conn->query($sql1);
                                while ($row1 = $result1->fetch_assoc()){
                                  ?>
                                  <option value="<?=$row1['id'];?>"><?=$row1['cohort'];?></option>
                                  <?php
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                      <?php
                        //}
                      ?>

                      <div class="row form-group">
                        <div class="col-md-2">
                          <label for="">Salutation</label>
                          <select name="salutation_id" id="" class="form-control">
                            <?php
                              if ($rqw['salutation_id'] > 0){
                            ?>
                                <option value="<?=$rqw['salutation_id'];?>"><?=getSalutation($conn, $rqw['salutation_id']);?></option>
                            <?php
                              } else {
                            ?>
                            <option value=""></option>
                            <?php
                              }
                            ?>
                            <?php
                              $sql1 = "select * 
                                       from salutations
                                       order by salutation asc";
                              $result1 = $conn->query($sql1);
                              while ($row1 = $result1->fetch_assoc()){
                            ?>
                            <option value="<?=$row1['id'];?>"><?=$row1['salutation'];?></option>
                            <?php
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <div class="row form-group">
                        <div class="col-md-12">
                          <label for="">Full name</label>
                          <input type="text" class="form-control" value="<?=$_SESSION['fullname'];?>" name="fullname">
                        </div>
                      </div>

                      <div class="row form-group">
                        <div class="col-md-12">
                          <label for="">Student ID</label>
                          <input type="text" class="form-control" value="<?=$_SESSION['studentId'];?>" name="studentId">
                        </div>
                      </div>

                      <div class="row form-group">
                        <div class="col-md-12">
                          <label for="">E-mail</label>
                          <input type="text" class="form-control" value="<?=$_SESSION['username'];?>" disabled>
                        </div>
                      </div>

                      <div class="row form-group">
                        <div class="col-md-12">
                          <button class="btn btn-success" type="submit" name="updateInfo">Update information</button>
                        </div>

                      </div>
                    </form>

                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="timeline">
                    <form action="" method="post">
                      <div class="row form-group">
                        <div class="col-md-12">
                          <label for="">Current password</label>
                          <input type="password" class="form-control" name="oldpassword" placeholder="Current password" ">
                        </div>
                      </div>

                      <div class="row form-group">
                        <div class="col-md-12">
                          <label for="">New password</label>
                          <input type="password" class="form-control" name="newpassword1" placeholder="New password" ">
                        </div>
                      </div>

                      <div class="row form-group">
                        <div class="col-md-12">
                          <label for="">Re-enter new password</label>
                          <input type="password" class="form-control" name="newpassword2" placeholder="Re-enter new password" ">
                        </div>
                      </div>

                      <div class="row form-group">
                        <div class="col-md-12">
                          <button class="btn btn-success" type="submit" name="changePassword">Change password</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputName" placeholder="Name">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputName2" placeholder="Name">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                        <div class="col-sm-10">
                          <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
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
