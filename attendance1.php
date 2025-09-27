<?php
session_start();
include_once 'database.php';

// Check if user is a teacher
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Teacher') {
  header('Location: ./logout.php');
}

 $message = '';
 $classSelected = false;
 $students = [];
 $reportData = [];
 $reportClass = '';
 $reportDate = '';
 $monthlyReport = [];
 $monthlyClass = '';
 $monthlyMonth = '';

// Handle class selection for taking attendance
if (isset($_POST['select_class'])) {
  $class_id = $_POST['class_id'];
  $classSelected = true;
  
  // Get students for the selected class
  $sql = "SELECT * FROM student WHERE classroom = '$class_id'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $students[] = $row;
    }
  } else {
    $message = "No students found in this class.";
  }
}

// Handle attendance submission
if (isset($_POST['submit_attendance'])) {
  $class_id = $_POST['class_id'];
  $date = date('Y-m-d');
  $teacher = $_SESSION['user'];
  $success = true;
  
  // Process each student
  foreach ($_POST['attendance'] as $student_id => $status) {
    // Check if attendance already exists for this student today
    $check_sql = "SELECT * FROM attendance1 WHERE student_id = '$student_id' AND date = '$date'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
      // Update existing record
      $update_sql = "UPDATE attendance1 SET status = '$status', recorded_by = '$teacher' 
                    WHERE student_id = '$student_id' AND date = '$date'";
      if (!$conn->query($update_sql)) {
        $success = false;
      }
    } else {
      // Insert new record
      $insert_sql = "INSERT INTO attendance1 (student_id, class_id, date, status, recorded_by) 
                    VALUES ('$student_id', '$class_id', '$date', '$status', '$teacher')";
      if (!$conn->query($insert_sql)) {
        $success = false;
      }
    }
  }
  
  if ($success) {
    $message = "Attendance recorded successfully!";
  } else {
    $message = "Error recording attendance: " . $conn->error;
  }
  
  // Get students again to show updated list
  $sql = "SELECT * FROM student WHERE classroom = '$class_id'";
  $result = $conn->query($sql);
  $students = [];
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $students[] = $row;
    }
  }
  $classSelected = true;
}

// Handle report generation
if (isset($_POST['generate_report'])) {
  $reportClass = $_POST['report_class'];
  $reportDate = $_POST['report_date'];
  
  // Get attendance data for the selected class and date
  $sql = "SELECT s.sid, s.fname, s.lname, a.status 
          FROM student s
          LEFT JOIN attendance1 a ON s.sid = a.student_id AND a.date = '$reportDate'
          WHERE s.classroom = '$reportClass'
          ORDER BY s.fname, s.lname";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $reportData[] = $row;
    }
  }
}

// Handle monthly report generation
if (isset($_POST['generate_monthly_report'])) {
  $monthlyClass = $_POST['monthly_class'];
  $monthlyMonth = $_POST['monthly_month'];
  
  // Get all students in the selected class
  $sql = "SELECT * FROM student WHERE classroom = '$monthlyClass'";
  $result = $conn->query($sql);
  
  $studentsInClass = [];
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $studentsInClass[] = $row;
    }
  }
  
  // Get attendance data for each student for the selected month
  foreach ($studentsInClass as $student) {
    $student_id = $student['sid'];
    
    $sql = "SELECT date, status FROM attendance1 
            WHERE student_id = '$student_id' 
            AND date LIKE '$monthlyMonth%' 
            ORDER BY date";
    $result = $conn->query($sql);
    
    $attendanceRecords = [];
    $presentDays = 0;
    $absentDays = 0;
    
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $attendanceRecords[] = $row;
        if ($row['status'] == 'Present') {
          $presentDays++;
        } else if ($row['status'] == 'Absent') {
          $absentDays++;
        }
      }
    }
    
    $totalDays = $presentDays + $absentDays;
    $attendancePercentage = ($totalDays > 0) ? round(($presentDays / $totalDays) * 100, 2) : 0;
    
    $monthlyReport[] = [
      'student' => $student,
      'attendanceRecords' => $attendanceRecords,
      'presentDays' => $presentDays,
      'absentDays' => $absentDays,
      'totalDays' => $totalDays,
      'attendancePercentage' => $attendancePercentage
    ];
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Attendance Management</title>
  <?php include_once 'header.php'; ?>
  <style>
    .nav-tabs {
      margin-bottom: 20px;
    }
    .tab-content {
      padding: 20px;
      border: 1px solid #ddd;
      border-top: none;
      border-radius: 0 0 4px 4px;
    }
    .attendance-stats {
      display: flex;
      justify-content: space-around;
      margin-bottom: 20px;
    }
    .stat-card {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      text-align: center;
      min-width: 150px;
    }
    .stat-value {
      font-size: 24px;
      font-weight: bold;
    }
    .stat-label {
      color: #666;
    }
    .progress {
      height: 10px;
      margin-bottom: 10px;
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
          <div class="col-md-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Attendance Management</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <?php if (!empty($message)): ?>
                  <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <!-- Tabs -->
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#take_attendance">Take Attendance</a></li>
                  <li><a data-toggle="tab" href="#view_reports">Daily Reports</a></li>
                  <li><a data-toggle="tab" href="#monthly_reports">Monthly Reports</a></li>
                </ul>
                
                <div class="tab-content">
                  <!-- Take Attendance Tab -->
                  <div id="take_attendance" class="tab-pane fade in active">
                    <?php if (!$classSelected): ?>
                      <!-- Class Selection Form -->
                      <form method="post" action="">
                        <div class="form-group">
                          <label for="class_id">Select Class:</label>
                          <select name="class_id" class="form-control" required>
                            <option value="">-- Select Class --</option>
                            <?php
                            $sql = "SELECT * FROM classroom";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                              while($row = $result->fetch_assoc()) {
                                echo "<option value='".$row["hno"]."'>".$row["title"]." (ID: ".$row["hno"].")</option>";
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <button type="submit" name="select_class" class="btn btn-primary">Take Attendance</button>
                      </form>
                    <?php else: ?>
                      <!-- Attendance Form -->
                      <form method="post" action="">
                        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                        <h3>Attendance for <?php echo date('F j, Y'); ?></h3>
                        
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Student ID</th>
                              <th>Name</th>
                              <th>Present</th>
                              <th>Absent</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($students as $student): ?>
                              <?php
                              // Check if attendance already exists for today
                              $today = date('Y-m-d');
                              $att_sql = "SELECT * FROM attendance1 WHERE student_id = '".$student['sid']."' AND date = '$today'";
                              $att_result = $conn->query($att_sql);
                              $current_status = '';
                              
                              if ($att_result->num_rows > 0) {
                                $att_row = $att_result->fetch_assoc();
                                $current_status = $att_row['status'];
                              }
                              ?>
                              <tr>
                                <td><?php echo $student['sid']; ?></td>
                                <td><?php echo $student['fname'] . ' ' . $student['lname']; ?></td>
                                <td>
                                  <input type="radio" name="attendance[<?php echo $student['sid']; ?>]" 
                                         value="Present" <?php echo ($current_status == 'Present') ? 'checked' : ''; ?> required>
                                </td>
                                <td>
                                  <input type="radio" name="attendance[<?php echo $student['sid']; ?>]" 
                                         value="Absent" <?php echo ($current_status == 'Absent') ? 'checked' : ''; ?> required>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                        
                        <div class="form-group">
                          <button type="submit" name="submit_attendance" class="btn btn-success">Submit Attendance</button>
                          <a href="attendance.php" class="btn btn-default">Select Different Class</a>
                        </div>
                      </form>
                    <?php endif; ?>
                  </div>
                  
                  <!-- View Reports Tab -->
                  <div id="view_reports" class="tab-pane fade">
                    <form method="post" action="" class="form-inline">
                      <div class="form-group">
                        <label for="report_class">Class:</label>
                        <select name="report_class" class="form-control" required>
                          <option value="">-- Select Class --</option>
                          <?php
                          $sql = "SELECT * FROM classroom";
                          $result = $conn->query($sql);
                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              $selected = ($reportClass == $row["hno"]) ? 'selected' : '';
                              echo "<option value='".$row["hno"]."' $selected>".$row["title"]." (ID: ".$row["hno"].")</option>";
                            }
                          }
                          ?>
                        </select>
                      </div>
                      
                      <div class="form-group">
                        <label for="report_date">Date:</label>
                        <input type="date" name="report_date" class="form-control" value="<?php echo $reportDate; ?>" required>
                      </div>
                      
                      <button type="submit" name="generate_report" class="btn btn-primary">Generate Report</button>
                    </form>
                    
                    <?php if (!empty($reportData)): ?>
                      <h3>Attendance Report for <?php echo $reportDate; ?></h3>
                      
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($reportData as $student): ?>
                            <tr>
                              <td><?php echo $student['sid']; ?></td>
                              <td><?php echo $student['fname'] . ' ' . $student['lname']; ?></td>
                              <td>
                                <?php if ($student['status'] == 'Present'): ?>
                                  <span class="label label-success">Present</span>
                                <?php elseif ($student['status'] == 'Absent'): ?>
                                  <span class="label label-danger">Absent</span>
                                <?php else: ?>
                                  <span class="label label-default">Not Recorded</span>
                                <?php endif; ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                      
                      <div class="form-group">
                        <button class="btn btn-default" onclick="window.print()">Print Report</button>
                      </div>
                    <?php endif; ?>
                  </div>
                  
                  <!-- Monthly Reports Tab -->
                  <div id="monthly_reports" class="tab-pane fade">
                    <form method="post" action="" class="form-inline">
                      <div class="form-group">
                        <label for="monthly_class">Class:</label>
                        <select name="monthly_class" class="form-control" required>
                          <option value="">-- Select Class --</option>
                          <?php
                          $sql = "SELECT * FROM classroom";
                          $result = $conn->query($sql);
                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                              $selected = ($monthlyClass == $row["hno"]) ? 'selected' : '';
                              echo "<option value='".$row["hno"]."' $selected>".$row["title"]." (ID: ".$row["hno"].")</option>";
                            }
                          }
                          ?>
                        </select>
                      </div>
                      
                      <div class="form-group">
                        <label for="monthly_month">Month:</label>
                        <input type="month" name="monthly_month" class="form-control" value="<?php echo $monthlyMonth; ?>" required>
                      </div>
                      
                      <button type="submit" name="generate_monthly_report" class="btn btn-primary">Generate Monthly Report</button>
                    </form>
                    
                    <?php if (!empty($monthlyReport)): ?>
                      <h3>Monthly Attendance Report for <?php echo date('F Y', strtotime($monthlyMonth)); ?></h3>
                      
                      <div class="attendance-stats">
                        <div class="stat-card">
                          <div class="stat-value"><?php echo count($monthlyReport); ?></div>
                          <div class="stat-label">Total Students</div>
                        </div>
                        <div class="stat-card">
                          <?php
                          $totalPresent = 0;
                          $totalDays = 0;
                          foreach ($monthlyReport as $report) {
                            $totalPresent += $report['presentDays'];
                            $totalDays = max($totalDays, $report['totalDays']);
                          }
                          $classAttendancePercentage = ($totalDays > 0) ? round(($totalPresent / ($totalDays * count($monthlyReport))) * 100, 2) : 0;
                          ?>
                          <div class="stat-value"><?php echo $classAttendancePercentage; ?>%</div>
                          <div class="stat-label">Class Attendance</div>
                        </div>
                      </div>
                      
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Present Days</th>
                            <th>Absent Days</th>
                            <th>Total Days</th>
                            <th>Attendance %</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($monthlyReport as $report): ?>
                            <tr>
                              <td><?php echo $report['student']['sid']; ?></td>
                              <td><?php echo $report['student']['fname'] . ' ' . $report['student']['lname']; ?></td>
                              <td><?php echo $report['presentDays']; ?></td>
                              <td><?php echo $report['absentDays']; ?></td>
                              <td><?php echo $report['totalDays']; ?></td>
                              <td>
                                <div class="progress">
                                  <div class="progress-bar <?php echo $report['attendancePercentage'] >= 75 ? 'progress-bar-success' : ($report['attendancePercentage'] >= 50 ? 'progress-bar-warning' : 'progress-bar-danger'); ?>" 
                                       role="progressbar" 
                                       style="width: <?php echo $report['attendancePercentage']; ?>%">
                                    <?php echo $report['attendancePercentage']; ?>%
                                  </div>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                      
                      <div class="form-group">
                        <button class="btn btn-default" onclick="window.print()">Print Report</button>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <?php include_once 'footer.php'; ?>
      
      <!-- Make sure Bootstrap tabs are working -->
      <script>
        $(document).ready(function() {
          // Activate tabs
          $('.nav-tabs a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
          });
        });
      </script>
    </div>
  </div>
</body>
</html>