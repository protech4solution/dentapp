<?php
  // get user role
  //print_r($_SESSION);
  //exit;
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->

  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="../logout.php" class="nav-link">Logout</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Brad Diesel
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">Call me whenever you can...</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                John Pierce
                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">I got your message bro</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Nora Silvester
                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">The subject goes here</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li>
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <?php
        // get all request
        $newRequest = 0;
        $newSession = 0;
        $numNotification = 0;

        if ($_SESSION['role'] == '2'){
          $sql1 = "SELECT *
                   FROM assessments
                   WHERE request_status = '2' AND assessor_id = '" . $_SESSION['user_id'] . "'";
        } else {
          $sql1 = "SELECT *
                   FROM assessments
                   WHERE request_status = '2' AND user_id = '" . $_SESSION['user_id'] . "'";
        }

        $rst1 = $conn->query($sql1);

        $newRequest = $rst1->num_rows;

        // get all request
        if ($_SESSION['role'] == '2'){
          $sql2 = "SELECT *
                   FROM assessments
                   WHERE request_status = '1' AND status != '1' AND assessor_id = '" . $_SESSION['user_id'] . "'";
        } else {
          $sql2 = "SELECT *
                   FROM assessments
                   WHERE request_status = '1' AND status != '1' AND user_id = '" . $_SESSION['user_id'] . "'";
        }

        $rst2 = $conn->query($sql2);

        $newSession = $rst2->num_rows;

        $numNotification = $newRequest + $newSession;

      ?>
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge"><?=$numNotification;?></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        <span class="dropdown-item dropdown-header"><?=$numNotification;?> Notifications</span>
        <div class="dropdown-divider"></div>
        <a href="request-new.php" class="dropdown-item">
          <i class="fas fa-envelope mr-2"></i> <?=$newRequest;?> new request
<!--          <span class="float-right text-muted text-sm">3 mins</span>-->
        </a>
        <div class="dropdown-divider"></div>
        <a href="session-incoming.php" class="dropdown-item">
          <i class="fas fa-calendar mr-2"></i> <?=$newSession;?> incoming session
<!--          <span class="float-right text-muted text-sm">12 hours</span>-->
        </a>
        <!--
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-file mr-2"></i> 3 new reports
          <span class="float-right text-muted text-sm">2 days</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        -->
      </div>
    </li>
  </ul>
</nav>
