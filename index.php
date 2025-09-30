<?php session_start();
include_once 'database.php';
if (!isset($_SESSION['user'])) {
  header('Location:./login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Dashboard | Student Management System</title>
  <link rel="icon" href="../img/favicon2.png">
  <?php include_once 'header.php'; ?>
  
  <style>
    .welcome-container {
      text-align: center;
      padding: 50px 0;
    }
    
    .welcome-message {
      font-size: 2rem;
      color: #333;
      margin-bottom: 10px;
    }
    
    .username {
      font-size: 1.5rem;
      color: #666;
    }
    
    .role-badge {
      display: inline-block;
      padding: 5px 15px;
      background: #f0f0f0;
      border-radius: 20px;
      margin-top: 15px;
      color: #555;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <?php include_once 'sidebar.php'; ?>
      </div>
      
      <?php include_once 'nav-menu.php'; ?>
      
      <!-- page content -->
      <div class="right_col" role="main">
        <?php if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Teacher'): ?>
          <!-- Admin/Teacher Dashboard - Original Design -->
          <div class="row">
            <div class="col-md-12">
              <div class="">
                <div class="x_content">
                  <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                      <div class="alert alert-success back-widget-set text-center">
                        <i class="fa fa-user fa-5x"></i>
                        <h3>
                          <?php 
                          $sql1="SELECT count(*) as a from student";
                          $result = $conn->query($sql1);
                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              echo $row['a'];
                            }
                          }
                          ?>
                        </h3>
                        Total Students
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                      <div class="alert alert-info back-widget-set text-center">
                        <i class="fa fa-black-tie fa-5x"></i>
                        <h3>
                          <?php 
                          $sql2="SELECT count(*) as a from teacher";
                          $result = $conn->query($sql2);
                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              echo $row['a'];
                            }
                          }
                          ?>
                        </h3>
                        Total Teachers
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                      <div class="alert alert-warning back-widget-set text-center">
                        <i class="fa fa-book fa-5x"></i>
                        <h3>
                          <?php 
                          $sql3="SELECT count(*) as a from subject";
                          $result = $conn->query($sql3);
                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              echo $row['a'];
                            }
                          }
                          ?>
                        </h3>
                        Total Subjects
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                      <div class="alert alert-danger back-widget-set text-center">
                        <i class="fa fa-users fa-5x"></i>
                        <h3>
                          <?php 
                          $sql4="SELECT count(*) as a from parent";
                          $result = $conn->query($sql4);
                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              echo $row['a'];
                            }
                          }
                          ?>
                        </h3>
                        Registered Parents
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        <?php else: ?>
          <!-- Student/Parent Dashboard - Simple Design -->
          <div class="welcome-container">
            <h1 class="welcome-message">Welcome, <?php echo ucfirst($_SESSION['user']); ?>!</h1>
            <p class="username">You are logged in as: <span class="role-badge"><?php echo $_SESSION['role']; ?></span></p>
          </div>
        <?php endif; ?>
      </div>
      <!-- /page content -->
      
      <!-- footer content -->
      <footer>
        <div class="pull-right">
          Student Management System 
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>
  <?php include_once 'footer.php'; ?>
</body>
</html>