<?php session_start();
include_once 'database.php';
if (!isset($_SESSION['user'])||$_SESSION['role']!='Teacher') {
  header('Location:./logout.php');
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
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Dashboard</title><link rel="icon" href="../img/favicon2.png">
  <!-- Tell the browser to be responsive to screen width -->
  <?php include_once 'header.php'; ?>
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
                          $sql = "SELECT * FROM student";
                          $result = $conn->query($sql);

                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              $class = (isset($_GET['update']) && $_GET['update'] == $row["sid"]) ? 'parent' : '';
                              echo "<tr class='{$class}'>
                                <td>" . $row["sid"] . "</td>
                                <td>" . $row["fname"] . " " . $row["lname"] . "</td>
                                <td>" . $row["bday"] . "</td>
                                <td>" . $row["gender"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>" . $row["address"] . "</td>
                                <td>" . $row["classroom"] . "</td>
                                <td>
                                  <a href='student.php?update=" . $row["sid"] . "'>
                                    <small class='btn btn-sm btn-primary'>Update</small>
                                  </a>
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
</body>
</html>