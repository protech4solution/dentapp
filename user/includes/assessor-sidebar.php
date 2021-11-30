<aside class="main-sidebar sidebar-light-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="../images/dentapp-icon.PNG" alt="AdminLTE Logo" class="" style="opacity: .8; width: 75px;">
    <!--<span class="brand-text font-weight-light">DentApp</span>-->
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../avatar/<?=$_SESSION['picture'];?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?=$_SESSION['fullname'];?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview menu-open">
          <a href="index.php" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="profile.php" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Profile
            </p>
          </a>
        </li>

        <li class="nav-header">ASSESSMENT</li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Request
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="request-all.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>All</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="request-new.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>New Request</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Session
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="session-incoming.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Incoming</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="session-completed.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Completed</p>
              </a>
            </li>
          </ul>
        </li>
        <!--
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon far fa-plus-square"></i>
            <p>
              Report & Feedback
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="pages/examples/login.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Login</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">MISCELLANEOUS</li>
        <li class="nav-item">
          <a href="https://adminlte.io/docs/3.0" class="nav-link">
            <i class="nav-icon fas fa-file"></i>
            <p>Documentation</p>
          </a>
        </li>
        -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
