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
            <h1>Booking</h1>
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
              <div class="col-md-12">
                <div class="form-group">
                  <label>Activity</label>
                  <select class="form-control select2" style="width: 100%;"
                          id="country-list"
                          onChange="getState();">
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
                      <select name="faculty_id"
                              id="state-list" class="form-control">
                        <option value="">Select observation</option>
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
          </form>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          Footer
        </div>
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
