<?php session_start();
include_once 'database.php';

// Check if user is a teacher or admin
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Teacher' && $_SESSION['role'] != 'Admin')) {
  header('Location:./logout.php');
}

// Handle delete student
if (isset($_GET['delete'])) {
  $student_id = $_GET['delete'];
  
  // Delete the student
  $sql = "DELETE FROM student WHERE sid = '$student_id'";
  
  if ($conn->query($sql) === TRUE) {
    // Redirect back to the student page with a success message
    header("Location: student.php?deleted=1");
    exit;
  } else {
    // Redirect back with an error message
    header("Location: student.php?error=1");
    exit;
  }
}

 $sid =$fname =$lname =$email= $classroom = $dob = $gender = $address = $parent=" ";

if(isset($_GET['update'])){
  $update = "SELECT * FROM student WHERE sid='".$_GET['update']."'";
  $result = $conn->query($update);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $sid = $row['sid'];
      $fname = $row['fname'];
      $lname = $row['lname'];
      $classroom = $row['classroom'];
      $email = $row['email'];
      $dob = date_format(new DateTime($row['bday']),'Y-m-d');
      $gender = $row['gender'];
      $address = $row['address'];
      $parent=$row['parent'];
    }
  }
}

// Handle form submission
if (isset($_POST['submit'])) {
  $sid = $_POST['sid'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];
  $classroom = $_POST['classroom'];
  $dob = date_format(new DateTime($_POST['dob']),'Y-m-d');
  $gender = $_POST['gender'];
  $address = $_POST['address'];
  $parent = $_POST['parent'];
  
  // Determine if we're updating or inserting
  $isUpdate = isset($_GET['update']);
  
  try {
    if ($isUpdate) {
      // Update existing student
      $sql = "UPDATE student SET 
              fname='".$fname."', 
              lname='".$lname."', 
              bday='".$dob."', 
              address='".$address."', 
              gender='".$gender."', 
              parent='".$parent."', 
              classroom='".$classroom."', 
              email='".$email."' 
              WHERE sid='".$sid."'";
      $action = "updated";
    } else {
      // Insert new student
      $sql = "INSERT INTO student (sid,fname,lname,bday,address,gender,parent,classroom,email) 
              VALUES ('".$sid."', '".$fname."', '".$lname."','".$dob."','".$address."','".$gender."','".$parent."','".$classroom."','".$email."')";
      $action = "added";
    }
    
    if ($conn->query($sql) === TRUE) {
      // Show success message
      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          var msg = document.getElementById('successmsg');
          if (msg) {
            msg.style.display='block';
            msg.innerHTML = '<h4><i class=\"icon fa fa-check\"></i> Success!</h4>Student ".$action." successfully';
          }
        });
      </script>";
      
      // If updating, refresh the page to show updated data in table
      if ($isUpdate) {
        header("Location: student.php?update=".$sid."&success=1");
        exit;
      }
    } else {
      // Show error message
      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          var msg = document.getElementById('errormsg');
          if (msg) {
            msg.style.display='block';
            msg.innerHTML = '<h4><i class=\"icon fa fa-ban\"></i> Error!</h4>".$conn->error."';
          }
        });
      </script>";
    }
  } catch (Exception $e) {
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        var msg = document.getElementById('errormsg');
        if (msg) {
          msg.style.display='block';
          msg.innerHTML = '<h4><i class=\"icon fa fa-ban\"></i> Error!</h4>".$e->getMessage()."';
        }
      });
    </script>";
  }
}

// Handle class filter
 $classFilter = '';
if (isset($_GET['class_filter']) && !empty($_GET['class_filter'])) {
    $classFilter = $_GET['class_filter'];
}

// Handle print request
if (isset($_GET['action']) && $_GET['action'] == 'print') {
    $printClass = isset($_GET['print_class']) ? $_GET['print_class'] : '';
    
    // Build query based on whether we're filtering by class
    if (!empty($printClass)) {
        $sql = "SELECT s.*, c.title as classroom_title 
                FROM student s 
                JOIN classroom c ON s.classroom = c.hno 
                WHERE s.classroom = '$printClass' 
                ORDER BY s.fname, s.lname";
        $classTitle = "Class: " . $printClass;
    } else {
        $sql = "SELECT s.*, c.title as classroom_title 
                FROM student s 
                JOIN classroom c ON s.classroom = c.hno 
                ORDER BY s.classroom, s.fname, s.lname";
        $classTitle = "All Classes";
    }
    
    $result = $conn->query($sql);
    
    // Output HTML content for printing
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Student List - <?php echo $classTitle; ?></title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                background-color: #fff;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .report-title {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 10px;
            }
            .class-name {
                font-size: 18px;
                margin-bottom: 20px;
            }
            .student-table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 20px;
            }
            .student-table th, .student-table td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            .student-table th {
                background-color: #f2f2f2;
                font-weight: bold;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                font-size: 12px;
            }
            .print-button {
                text-align: center;
                margin: 20px 0;
            }
            @media print {
                .print-button {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="report-title">Student Management System</div>
            <div class="class-name"><?php echo $classTitle; ?></div>
            <div>Generated on <?php echo date('F j, Y'); ?></div>
        </div>
        
        <table class="student-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Class</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['sid']); ?></td>
                            <td><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['bday'])); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['classroom_title']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No students found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer">
            Student Management System - Generated on <?php echo date('F j, Y'); ?>
        </div>
        
        <div class="print-button">
            <button onclick="window.print()">Print</button>
            <button onclick="window.close()">Close Window</button>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Dashboard</title>
  <link rel="icon" href="../img/favicon2.png">
  <!-- Tell the browser to be responsive to screen width -->
  <?php include_once 'header.php'; ?>
  <style>
    .filter-section {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .filter-form {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .filter-form label {
      font-weight: bold;
    }
    .print-btn {
      background-color: #337ab7;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
    }
    .print-btn:hover {
      background-color: #286090;
    }
    .delete-btn {
      background-color: #d9534f;
      color: white;
      border: none;
      padding: 4px 8px;
      border-radius: 3px;
      font-size: 12px;
      cursor: pointer;
      margin-left: 5px;
    }
    .delete-btn:hover {
      background-color: #c9302c;
    }
    .action-buttons {
      display: flex;
      gap: 5px;
    }
    .alert-container {
      margin-bottom: 20px;
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
        <div class="row">
          <div class="col-md-3">
            <div class="x_panel">
              <div class="x_title">
                <h2><?php echo (isset($_GET['update']))?"Update student":"Add student"; ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <!-- Alert Messages -->
                <div class="alert-container">
                  <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <h4><i class="icon fa fa-check"></i> Success!</h4> Student deleted successfully.
                    </div>
                  <?php endif; ?>
                </div>
                
                <!-- Success Message -->
                <div class="alert alert-success alert-dismissible" style="display: none;" id="successmsg">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                </div>
                
                <!-- Error Message -->
                <div class="alert alert-danger alert-dismissible" style="display: none;" id="errormsg">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                </div>

                <form role="form" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputPassword1">Student ID</label>
                      <input name="sid" type="text" class="form-control" id="exampleInputPassword1" required value="<?php echo htmlspecialchars($sid); ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">First Name</label>
                      <input name="fname" type="text" class="form-control" id="exampleInputPassword1" required value="<?php echo htmlspecialchars($fname); ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">Last Name</label>
                      <input name="lname" type="text" class="form-control" id="exampleInputPassword1" required value="<?php echo htmlspecialchars($lname); ?>">
                    </div>

                    <div class="form-group">
                      <label>Date of Birth</label>
                      <div class="input-group date">
                        <input type="date" name='dob' class="form-control pull-right" id="datepicker" placeholder="Select Student's Data of Birth" value="<?php echo htmlspecialchars($dob); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">Gender</label>
                      <div class="radio">
                        <label><input type="radio" name="gender" value="Male" <?php if($gender=='Male'){echo 'checked';} ?>> Male</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" name="gender" value="Female" <?php if($gender=='Female'){echo 'checked';} ?>> Female</label>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleInputPassword1">Email</label>
                      <input name="email" type="email" class="form-control" id="exampleInputPassword1" required value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleFormControlTextarea1">Address</label>
                      <textarea name="address" class="form-control" id="exampleFormControlTextarea1" rows="2"><?php echo htmlspecialchars($address); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label>Class Room</label>
                      <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" name="classroom">
                        <option>Select Class Room</option>
                        <?php
                        $sql = "SELECT * FROM classroom";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while($row = $result->fetch_assoc()) {
                            echo "<option ";
                            if($classroom==$row["hno"]){
                              echo 'selected="selected"';
                            }
                            echo " value='".$row["hno"]."' >".$row["title"]."_ID:".$row["hno"]."</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <label>Parent</label>
                      <select name="parent" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                        <option value="0">Select Parent</option>
                        <?php
                        $sql = "SELECT * FROM parent";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while($row = $result->fetch_assoc()) {
                            echo "<option ";
                            if($parent==$row["pid"]){
                              echo 'selected="selected"';
                            }
                            echo " value='".$row["pid"]."' >".$row["fname"]." ".$row["lname"]." - ID:".$row["pid"]."</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">
                      <?php echo (isset($_GET['update']))?"Update Student":"Add Student"; ?>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          
          <div class="col-md-9">
            <div class="x_panel">
              <div class="x_title">
                <h2>All <small>Students</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <!-- Class Filter Section -->
                <div class="filter-section">
                  <form method="get" action="" class="filter-form">
                    <label for="class_filter">Filter by Class:</label>
                    <select name="class_filter" class="form-control" style="width: 200px;" onchange="this.form.submit()">
                      <option value="">All Classes</option>
                      <?php
                      $sql = "SELECT * FROM classroom";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                          $selected = ($classFilter == $row["hno"]) ? 'selected' : '';
                          echo "<option value='".$row["hno"]."' $selected>".$row["title"]."</option>";
                        }
                      }
                      ?>
                    </select>
                  </form>
                  
                  <button type="button" class="print-btn" onclick="window.open('student.php?action=print<?php echo !empty($classFilter) ? '&print_class=' . $classFilter : ''; ?>', '_blank')">
                    <i class="fa fa-print"></i> Print
                  </button>
                </div>
                
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card-box table-responsive">
                      <p class="text-muted font-13 m-b-30">
                        Student Management System
                      </p>
                      <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                          <tr>
                            <th>SID</th>
                            <th>Name</th>
                            <th>DOB</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Classroom</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          // Build query based on whether we're filtering by class
                          if (!empty($classFilter)) {
                              $sql = "SELECT s.*, c.title as classroom_title 
                                      FROM student s 
                                      JOIN classroom c ON s.classroom = c.hno 
                                      WHERE s.classroom = '$classFilter' 
                                      ORDER BY s.fname, s.lname";
                          } else {
                              $sql = "SELECT s.*, c.title as classroom_title 
                                      FROM student s 
                                      JOIN classroom c ON s.classroom = c.hno 
                                      ORDER BY s.classroom, s.fname, s.lname";
                          }
                          
                          $result = $conn->query($sql);

                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              echo "<tr>
                                <td>" . $row["sid"] . "</td>
                                <td>" . $row["fname"] . " " . $row["lname"] . "</td>
                                <td>" . date('M d, Y', strtotime($row["bday"])) . "</td>
                                <td>" . $row["gender"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>" . $row["address"] . "</td>
                                <td>" . $row["classroom_title"] . "</td>
                                <td>
                                  <div class='action-buttons'>
                                    <a href='student.php?update=" . $row["sid"] . "'>
                                      <small class='btn btn-sm btn-primary'>Update</small>
                                    </a>
                                    <button class='delete-btn' onclick='confirmDelete(\"" . $row["sid"] . "\")'>
                                      <i class='fa fa-trash'></i> Delete
                                    </button>
                                  </div>
                                </td>
                              </tr>";
                            }
                          } else {
                            echo "<tr><td colspan='8' class='text-center'>No students found</td></tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <!-- footer content -->
      <footer>
        <div class="pull-right">
          Student Management System <a href="https://colorlib.com"></a>
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>
  <?php include_once 'footer.php'; ?>
  
  <script>
    function confirmDelete(studentId) {
      if (confirm("Are you sure you want to delete this student? This action cannot be undone.")) {
        window.location.href = "student.php?delete=" + studentId;
      }
    }
  </script>
</body>
</html>