<?php
session_start();
include_once 'database.php';

// Check if user is a student
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Student') {
  header('Location: ./logout.php');
}

// Get student ID from session
 $username = $_SESSION['user'];
 $uid = $_SESSION['uid']; // Get the UID from session

// Try to find the student in the database
 $student_found = false;
 $student_id = null;
 $student_info = [];

// Try by student ID (sid) first using the uid from session
 $sql = "SELECT * FROM student WHERE sid = ?";
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("s", $uid);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student_info = $result->fetch_assoc();
    $student_id = $student_info['sid'];
    $student_found = true;
} 
// Try by email if not found by sid
else {
    $sql = "SELECT * FROM student WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $student_info = $result->fetch_assoc();
        $student_id = $student_info['sid'];
        $student_found = true;
    }
    // Try by first and last name combined if not found by email
    else {
        // Split the username into first and last name
        $name_parts = explode(' ', $username);
        if (count($name_parts) >= 2) {
            $fname = $name_parts[0];
            $lname = implode(' ', array_slice($name_parts, 1));
            
            $sql = "SELECT * FROM student WHERE fname = ? AND lname = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $fname, $lname);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $student_info = $result->fetch_assoc();
                $student_id = $student_info['sid'];
                $student_found = true;
            }
        }
    }
}

// If student is still not found, show error with more details
if (!$student_found) {
    echo "<div class='alert alert-danger'>";
    echo "<h3>Error: Student not found in database</h3>";
    echo "<p>Username: " . htmlspecialchars($username) . "</p>";
    echo "<p>User ID: " . htmlspecialchars($uid) . "</p>";
    echo "<p>Please contact your administrator.</p>";
    
    // Debug information (remove in production)
    if (isset($_SESSION['role'])) {
        echo "<p>Session Role: " . htmlspecialchars($_SESSION['role']) . "</p>";
    }
    
    echo "</div>";
    
    // Include footer and exit
    include_once 'footer.php';
    exit;
}

// Get attendance records for the student
 $sql = "SELECT a.date, a.status, c.title as class_name 
       FROM attendance1 a
       JOIN classroom c ON a.class_id = c.hno
       WHERE a.student_id = ?
       ORDER BY a.date DESC";
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("s", $student_id);
 $stmt->execute();
 $result = $stmt->get_result();

// Calculate attendance statistics
 $total_days = 0;
 $present_days = 0;
 $absent_days = 0;

 $attendance_records = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $attendance_records[] = $row;
        $total_days++;
        if ($row['status'] == 'Present') {
            $present_days++;
        } else if ($row['status'] == 'Absent') {
            $absent_days++;
        }
    }
}

 $attendance_percentage = ($total_days > 0) ? round(($present_days / $total_days) * 100, 2) : 0;

// Handle month filter
 $selected_month = isset($_GET['month']) ? $_GET['month'] : '';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Attendance</title>
  <?php include_once 'header.php'; ?>
  <style>
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
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .stat-value {
      font-size: 24px;
      font-weight: bold;
    }
    .stat-label {
      color: #666;
      margin-top: 5px;
    }
    .progress {
      height: 10px;
      margin-bottom: 10px;
    }
    .attendance-card {
      background: white;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      padding: 15px;
      margin-bottom: 15px;
    }
    .date-badge {
      background: #f8f9fa;
      padding: 5px 10px;
      border-radius: 15px;
      font-size: 12px;
      margin-right: 10px;
    }
    .present-badge {
      background-color: #d4edda;
      color: #155724;
    }
    .absent-badge {
      background-color: #f8d7da;
      color: #721c24;
    }
    .month-filter {
      margin-bottom: 20px;
    }
    .attendance-list {
      max-height: 400px;
      overflow-y: auto;
    }
    .no-records {
      text-align: center;
      padding: 20px;
      color: #666;
    }
    .monthly-stats {
      margin-top: 20px;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 5px;
    }
    .monthly-stats h4 {
      margin-top: 0;
      margin-bottom: 15px;
    }
    @media print {
      .no-print {
        display: none;
      }
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
                <h2>My Attendance</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div class="alert alert-info">
                  <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student_id); ?></p>
                  <p><strong>Name:</strong> <?php echo htmlspecialchars($student_info['fname'] . ' ' . $student_info['lname']); ?></p>
                  <p><strong>Class:</strong> <?php echo htmlspecialchars($student_info['classroom']); ?></p>
                </div>
                
                <div class="attendance-stats">
                  <div class="stat-card">
                    <div class="stat-value"><?php echo $total_days; ?></div>
                    <div class="stat-label">Total Days</div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-value"><?php echo $present_days; ?></div>
                    <div class="stat-label">Present Days</div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-value"><?php echo $absent_days; ?></div>
                    <div class="stat-label">Absent Days</div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-value"><?php echo $attendance_percentage; ?>%</div>
                    <div class="stat-label">Attendance</div>
                  </div>
                </div>
                
                <div class="progress">
                  <div class="progress-bar <?php echo $attendance_percentage >= 75 ? 'progress-bar-success' : ($attendance_percentage >= 50 ? 'progress-bar-warning' : 'progress-bar-danger'); ?>" 
                           role="progressbar" 
                           style="width: <?php echo $attendance_percentage; ?>%">
                    <?php echo $attendance_percentage; ?>%
                  </div>
                </div>
                
                <div class="month-filter">
                  <form method="get" action="" class="form-inline">
                    <div class="form-group">
                      <label for="month_filter">Filter by Month:</label>
                      <select name="month" class="form-control" onchange="this.form.submit()">
                        <option value="">All Months</option>
                        <?php
                        // Get all available months from attendance records
                        $months_sql = "SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') as month FROM attendance1 WHERE student_id = ? ORDER BY month DESC";
                        $months_stmt = $conn->prepare($months_sql);
                        $months_stmt->bind_param("s", $student_id);
                        $months_stmt->execute();
                        $months_result = $months_stmt->get_result();
                        
                        if ($months_result->num_rows > 0) {
                            while($month_row = $months_result->fetch_assoc()) {
                                $month_value = $month_row['month'];
                                $month_name = date('F Y', strtotime($month_value . '-01'));
                                $selected = ($selected_month == $month_value) ? 'selected' : '';
                                echo "<option value='$month_value' $selected>$month_name</option>";
                            }
                        }
                        ?>
                      </select>
                    </div>
                  </form>
                </div>
                
                <div class="attendance-list">
                  <?php if (empty($attendance_records)): ?>
                    <div class="no-records">
                      <p>No attendance records found.</p>
                    </div>
                  <?php else: ?>
                    <?php
                    // Filter by month if selected
                    $filtered_records = $attendance_records;
                    if (!empty($selected_month)) {
                        $filtered_records = array_filter($attendance_records, function($record) use ($selected_month) {
                            return substr($record['date'], 0, 7) == $selected_month;
                        });
                    }
                    
                    if (empty($filtered_records)):
                    ?>
                    <div class="no-records">
                      <p>No attendance records found for the selected month.</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($filtered_records as $record): ?>
                      <div class="attendance-card">
                        <div class="row">
                          <div class="col-md-8">
                            <strong><?php echo date('F j, Y', strtotime($record['date'])); ?></strong>
                            <div class="text-muted"><?php echo $record['class_name']; ?></div>
                          </div>
                          <div class="col-md-4 text-right">
                            <?php if ($record['status'] == 'Present'): ?>
                              <span class="date-badge present-badge">Present</span>
                            <?php else: ?>
                              <span class="date-badge absent-badge">Absent</span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
                
                <?php if (!empty($selected_month) && !empty($filtered_records)): ?>
                <div class="monthly-stats no-print">
                  <h4>Monthly Statistics for <?php echo date('F Y', strtotime($selected_month . '-01')); ?></h4>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="stat-card">
                        <div class="stat-value">
                          <?php 
                          $month_present = 0;
                          foreach ($filtered_records as $record) {
                              if ($record['status'] == 'Present') $month_present++;
                          }
                          echo $month_present;
                          ?>
                        </div>
                        <div class="stat-label">Present Days</div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="stat-card">
                        <div class="stat-value">
                          <?php 
                          $month_absent = 0;
                          foreach ($filtered_records as $record) {
                              if ($record['status'] == 'Absent') $month_absent++;
                          }
                          echo $month_absent;
                          ?>
                        </div>
                        <div class="stat-label">Absent Days</div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="stat-card">
                        <div class="stat-value">
                          <?php 
                          $month_total = $month_present + $month_absent;
                          echo $month_total;
                          ?>
                        </div>
                        <div class="stat-label">Total Days</div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="stat-card">
                        <div class="stat-value">
                          <?php 
                          $month_percentage = $month_total > 0 ? round(($month_present / $month_total) * 100, 2) : 0;
                          echo $month_percentage . "%";
                          ?>
                        </div>
                        <div class="stat-label">Attendance %</div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endif; ?>
                
                <div class="text-center no-print">
                  <button class="btn btn-default" onclick="window.print()">Print Attendance</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <?php include_once 'footer.php'; ?>
    </div>
  </div>
</body>
</html>