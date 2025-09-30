<?php session_start();

include_once 'database.php';

// Check if user is a teacher or admin
if (!isset($_SESSION['user']) || ($_SESSION['role'] != 'Teacher' && $_SESSION['role'] != 'Admin')) {
  header('Location:./logout.php');
}

// Handle delete parent
if (isset($_GET['delete'])) {
  $parent_id = $_GET['delete'];
  
  // Delete the parent
  $sql = "DELETE FROM parent WHERE pid = '$parent_id'";
  
  if ($conn->query($sql) === TRUE) {
    // Redirect back to the parent page with a success message
    header("Location: parent.php?deleted=1");
    exit;
  } else {
    // Redirect back with an error message
    header("Location: parent.php?error=1");
    exit;
  }
}

 $pid =$fname =$lname=$nic=$email=$contact=$occupation = $classroom = $dob = $gender = $address = $parent=" ";

if(isset($_GET['update'])){
  $update = "SELECT * FROM parent WHERE pid='".$_GET['update']."'";
  $result = $conn->query($update);

  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $pid = $row['pid'];
      $nic = $row['nic'];
      $fname = $row['fname'];
      $lname = $row['lname'];
      $contact = $row['contact'];
      $occupation = $row['job'];
      $gender = $row['gender'];
      $address = $row['address'];
      $email=$row['email'];
    }
  }
}

// Handle form submission for adding parent
if (!isset($_GET['update']) && isset($_POST['submit'])) {
  $nic = $_POST['nic'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $gender = $_POST['gender'];
  $address = $_POST['address'];
  $email = $_POST['email'];
  $job = $_POST['job'];
  $contact = $_POST['contact'];

  try {
    $sql = "INSERT INTO parent (fname,lname,address,gender,job,contact,nic,email) VALUES ( '".$fname."', '".$lname."','".$address."','".$gender."','".$job."','".$contact."','".$nic."','".$email."')";

    if ($conn->query($sql) === TRUE) {
      // Redirect to show success message
      header("Location: parent.php?success=1&action=added");
      exit;
    }
  } catch (Exception $e) {
    // Handle exception
  }
}

// Handle form submission for updating parent
elseif (isset($_GET['update']) && isset($_POST['submit'])) {
  $nic = $_POST['nic'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $gender = $_POST['gender'];
  $address = $_POST['address'];
  $email = $_POST['email'];
  $job = $_POST['job'];
  $contact = $_POST['contact'];

  try {
    $sql = "UPDATE parent SET fname='".$fname."',lname='".$lname."',address='".$address."',gender='".$gender."',job='".$job."',contact='".$contact."',email='".$email."',nic='".$nic."' WHERE pid =".$pid;

    if ($conn->query($sql) === TRUE) {
      // Redirect to show success message
      header("Location: parent.php?update=".$pid."&success=1&action=updated");
      exit;
    }
  } catch (Exception $e) {
    // Handle exception
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Parent Management</title>
  <link rel="icon" href="../img/favicon2.png">
  <!-- Tell the browser to be responsive to screen width -->
  <?php include_once 'header.php'; ?>
  <style>
    .alert-container {
      margin-bottom: 20px;
    }
    .action-buttons {
      display: flex;
      gap: 5px;
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
    .student-names {
      font-size: 12px;
      color: #666;
      margin-top: 5px;
    }
    .student-label {
      font-weight: bold;
      color: #333;
    }
    .no-students {
      font-style: italic;
      color: #999;
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
        <!-- Alert Messages -->
        <div class="alert-container">
          <?php if (isset($_GET['success']) && isset($_GET['action'])): ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-check"></i> Success!</h4> Parent <?php echo $_GET['action']; ?> successfully.
            </div>
          <?php endif; ?>
          
          <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-check"></i> Success!</h4> Parent deleted successfully.
            </div>
          <?php endif; ?>
          
          <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-ban"></i> Error!</h4> Operation failed. Please try again.
            </div>
          <?php endif; ?>
        </div>
        
        <div class="row">
          <div class="col-md-3">
            <div class="x_panel">
              <div class="x_title">
                <h2><?php echo (isset($_GET['update']))?"Update parent":"Add parent"; ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <form role="form" method="POST" >
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputPassword1">First Name</label>
                      <input name="fname" type="text" class="form-control" id="exampleInputPassword1" required value="<?php echo htmlspecialchars($fname); ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">Last Name</label>
                      <input name="lname" type="text" class="form-control" id="exampleInputPassword1" required value="<?php echo htmlspecialchars($lname); ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">Adhar card</label>
                      <input name="nic" type="text" class="form-control" id="exampleInputPassword1" required value="<?php echo htmlspecialchars($nic); ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">Gender</label>
                      <div class="radio">
                        <label style="width: 100px"><input type="radio" name="gender" value="Male" <?php if($gender=='Male'){echo 'checked';} ?>> Male</label>
                        <label style="width: 100px"><input type="radio" name="gender" value="Female" <?php if($gender=='Female'){echo 'checked';} ?>> Female</label>
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
                      <label for="exampleInputPassword1">Contact</label>
                      <input name="contact" type="text" class="form-control" id="exampleInputPassword1" required value="<?php echo htmlspecialchars($contact); ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">Occupation</label>
                      <input name="job" type="text" class="form-control" id="exampleInputPassword1" required value="<?php echo htmlspecialchars($occupation); ?>">
                    </div>
                  </div>
                  <!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">
                      <?php echo (isset($_GET['update']))?"Update Parent":"Add Parent"; ?>
                    </button>
                    <?php if (isset($_GET['update'])): ?>
                      <a href="parent.php" class="btn btn-default">Cancel</a>
                    <?php endif; ?>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-md-9">
            <div class="x_panel">
              <div class="x_title">
                <h2>All <small>parents</small></h2>
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
                            <th>Parent ID</th>
                            <th>Full Name</th>
                            <th>Adhar card</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Occupation</th>
                            <th>Students</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sql = "SELECT * FROM parent";
                          $result = $conn->query($sql);

                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              $class = (isset($_GET['update']) && $_GET['update'] == $row["pid"])?'parent':'';
                              
                              // Get students associated with this parent
                              $parent_id = $row['pid'];
                              $student_sql = "SELECT fname, lname FROM student WHERE parent = '$parent_id'";
                              $student_result = $conn->query($student_sql);
                              
                              $student_names = '';
                              if ($student_result->num_rows > 0) {
                                $students = [];
                                while($student_row = $student_result->fetch_assoc()) {
                                  $students[] = $student_row['fname'] . ' ' . $student_row['lname'];
                                }
                                $student_names = implode(', ', $students);
                              } else {
                                $student_names = '<span class="no-students">No students</span>';
                              }
                              
                              echo "<tr class='{$class}'>
                                <td>" . $row["pid"] . "</td>
                                <td>" . $row["fname"] . " " . $row["lname"] . "</td>
                                <td>" . $row["nic"] . "</td>
                                <td>" . $row["gender"] . "</td>
                                <td>" . $row["address"] . "</td>
                                <td>" . $row["contact"] . "</td>
                                <td>" . $row["job"] . "</td>
                                <td>
                                  <div class='student-names'>
                                    <span class='student-label'>Students:</span> " . $student_names . "
                                  </div>
                                </td>
                                <td>
                                  <div class='action-buttons'>
                                    <a href='parent.php?update=" . $row["pid"] . "'>
                                      <small class='btn btn-sm btn-primary'>Update</small>
                                    </a>
                                    <button class='delete-btn' onclick='confirmDelete(\"" . $row["pid"] . "\")'>
                                      <i class='fa fa-trash'></i> Delete
                                    </button>
                                  </div>
                                </td>
                              </tr>";
                            }
                          } else {
                            echo "<tr><td colspan='9' class='text-center'>No parents found</td></tr>";
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
      <!-- /.box -->

    </div>
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

<script>
  function confirmDelete(parentId) {
    if (confirm("Are you sure you want to delete this parent? This action cannot be undone.")) {
      window.location.href = "parent.php?delete=" + parentId;
    }
  }
</script>

</body>
</html>