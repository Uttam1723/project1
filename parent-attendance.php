<?php
session_start();
include_once 'database.php';

// Check if user is a parent
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Parent') {
  header('Location: ./logout.php');
}

// Get parent ID from session - using the correct session variable name
// Based on the student attendance code, it seems like the session variable might be 'uid' or 'user'
 $parent_id = $_SESSION['uid'] ?? $_SESSION['user'] ?? null; // Use null coalescing to avoid undefined index

if ($parent_id === null) {
    // Handle case where parent ID is not found in session
    echo "<div class='alert alert-danger'>";
    echo "<h3>Error: Parent ID not found in session</h3>";
    echo "<p>Please log in again or contact your administrator.</p>";
    echo "</div>";
    include_once 'footer.php';
    exit;
}

// Get parent information - using the correct column name
// Based on the screenshots, we need to determine the correct primary key column name
// Let's try 'pid' (parent ID) or 'id' if it exists
try {
    // First, let's check the structure of the parent table
    $table_check_sql = "DESCRIBE parent";
    $table_result = $conn->query($table_check_sql);
    
    $id_column = 'id'; // Default column name
    while ($column = $table_result->fetch_assoc()) {
        if ($column['Field'] === 'pid') {
            $id_column = 'pid';
            break;
        } else if ($column['Key'] === 'PRI') {
            $id_column = $column['Field'];
            break;
        }
    }
    
    // Now get parent information using the correct column name
    $parent_sql = "SELECT * FROM parent WHERE $id_column = ?";
    $parent_stmt = $conn->prepare($parent_sql);
    $parent_stmt->bind_param("s", $parent_id); // Using "s" as it might be a string
    $parent_stmt->execute();
    $parent_result = $parent_stmt->get_result();

    if ($parent_result->num_rows > 0) {
        $parent_info = $parent_result->fetch_assoc();
    } else {
        // Parent not found in database
        echo "<div class='alert alert-danger'>";
        echo "<h3>Error: Parent account not found in database</h3>";
        echo "<p>Please contact your administrator.</p>";
        echo "</div>";
        include_once 'footer.php';
        exit;
    }
} catch (Exception $e) {
    // Handle database errors
    echo "<div class='alert alert-danger'>";
    echo "<h3>Database Error</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
    include_once 'footer.php';
    exit;
}

// Get students associated with this parent
// Using the correct column name for parent reference in student table
try {
    // Check if the parent column exists in the student table
    $table_check_sql = "DESCRIBE student";
    $table_result = $conn->query($table_check_sql);
    
    $parent_column = 'parent'; // Default column name
    $found_parent_column = false;
    
    while ($column = $table_result->fetch_assoc()) {
        if ($column['Field'] === 'parent') {
            $found_parent_column = true;
            break;
        }
    }
    
    if (!$found_parent_column) {
        throw new Exception("Parent reference column not found in student table");
    }
    
    $students_sql = "SELECT * FROM student WHERE parent = ?";
    $students_stmt = $conn->prepare($students_sql);
    $students_stmt->bind_param("s", $parent_id); // Using "s" as it might be a string
    $students_stmt->execute();
    $students_result = $students_stmt->get_result();
} catch (Exception $e) {
    // Handle database errors
    echo "<div class='alert alert-danger'>";
    echo "<h3>Database Error</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
    include_once 'footer.php';
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Children's Attendance</title>
  <?php include_once 'header.php'; ?>
  <style>
    .student-card {
      background: white;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      padding: 20px;
      margin-bottom: 25px;
    }
    .student-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }
    .student-name {
      font-size: 18px;
      font-weight: bold;
      margin: 0;
    }
    .student-info {
      color: #666;
      font-size: 14px;
    }
    .attendance-stats {
      display: flex;
      justify-content: space-around;
      margin: 15px 0;
    }
    .stat-card {
      background: #f8f9fa;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      min-width: 100px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .stat-value {
      font-size: 20px;
      font-weight: bold;
    }
    .stat-label {
      color: #666;
      font-size: 12px;
      margin-top: 5px;
    }
    .progress {
      height: 10px;
      margin-bottom: 15px;
    }
    .attendance-table {
      margin-top: 15px;
    }
    .month-filter {
      margin: 15px 0;
    }
    .no-records {
      text-align: center;
      padding: 20px;
      color: #666;
    }
    .label-success {
      background-color: #d4edda;
      color: #155724;
    }
    .label-danger {
      background-color: #f8d7da;
      color: #721c24;
    }
    .print-btn {
      margin-top: 15px;
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
                <h2>Children's Attendance Records</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div class="alert alert-info">
                  <p><strong>Parent Name:</strong> <?php echo htmlspecialchars($parent_info['fname'] . ' ' . ($parent_info['lname'] ?? '')); ?></p>
                  <p><strong>Email:</strong> <?php echo htmlspecialchars($parent_info['email'] ?? ''); ?></p>
                </div>
                
                <?php if ($students_result->num_rows > 0): ?>
                  <?php while($student = $students_result->fetch_assoc()): ?>
                    <div class="student-card">
                      <div class="student-header">
                        <div>
                          <h3 class="student-name"><?php echo htmlspecialchars($student['fname'] . ' ' . ($student['lname'] ?? '')); ?></h3>
                          <div class="student-info">
                            ID: <?php echo htmlspecialchars($student['sid']); ?> | 
                            Class: <?php echo htmlspecialchars($student['classroom']); ?>
                          </div>
                        </div>
                        <div class="no-print">
                          <button class="btn btn-default btn-sm" onclick="window.print()">
                            <i class="fa fa-print"></i> Print
                          </button>
                        </div>
                      </div>
                      
                      <?php
                      // Get attendance records for this student
                      $att_sql = "SELECT a.date, a.status, c.title as class_name 
                                  FROM attendance1 a
                                  JOIN classroom c ON a.class_id = c.hno
                                  WHERE a.student_id = ?
                                  ORDER BY a.date DESC";
                      $att_stmt = $conn->prepare($att_sql);
                      $att_stmt->bind_param("s", $student['sid']);
                      $att_stmt->execute();
                      $att_result = $att_stmt->get_result();
                      
                      // Calculate attendance statistics
                      $total_days = 0;
                      $present_days = 0;
                      $absent_days = 0;
                      
                      if ($att_result->num_rows > 0) {
                          while($row = $att_result->fetch_assoc()) {
                              $total_days++;
                              if ($row['status'] == 'Present') {
                                  $present_days++;
                              } else if ($row['status'] == 'Absent') {
                                  $absent_days++;
                              }
                          }
                      }
                      
                      $attendance_percentage = ($total_days > 0) ? round(($present_days / $total_days) * 100, 2) : 0;
                      
                      // Reset result pointer for displaying records
                      $att_stmt->execute();
                      $att_result = $att_stmt->get_result();
                      ?>
                      
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
                      
                      <div class="month-filter no-print">
                        <form method="get" action="" class="form-inline">
                          <div class="form-group">
                            <label for="month_filter_<?php echo $student['sid']; ?>">Filter by Month:</label>
                            <select name="month_<?php echo $student['sid']; ?>" class="form-control" onchange="this.form.submit()">
                              <option value="">All Months</option>
                              <?php
                              // Get all available months from attendance records for this student
                              $months_sql = "SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') as month FROM attendance1 WHERE student_id = ? ORDER BY month DESC";
                              $months_stmt = $conn->prepare($months_sql);
                              $months_stmt->bind_param("s", $student['sid']);
                              $months_stmt->execute();
                              $months_result = $months_stmt->get_result();
                              
                              if ($months_result->num_rows > 0) {
                                  while($month_row = $months_result->fetch_assoc()) {
                                      $month_value = $month_row['month'];
                                      $month_name = date('F Y', strtotime($month_value . '-01'));
                                      $selected_month = isset($_GET['month_' . $student['sid']]) ? $_GET['month_' . $student['sid']] : '';
                                      $selected = ($selected_month == $month_value) ? 'selected' : '';
                                      echo "<option value='$month_value' $selected>$month_name</option>";
                                  }
                              }
                              ?>
                            </select>
                          </div>
                        </form>
                      </div>
                      
                      <div class="attendance-table">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Date</th>
                              <th>Class</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if ($att_result->num_rows > 0): ?>
                              <?php 
                              // Filter by month if selected
                              $selected_month = isset($_GET['month_' . $student['sid']]) ? $_GET['month_' . $student['sid']] : '';
                              
                              while($row = $att_result->fetch_assoc()): 
                                // Skip records if month filter is active and doesn't match
                                if (!empty($selected_month) && substr($row['date'], 0, 7) != $selected_month) {
                                    continue;
                                }
                              ?>
                                <tr>
                                  <td><?php echo date('F j, Y', strtotime($row['date'])); ?></td>
                                  <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                  <td>
                                    <?php if ($row['status'] == 'Present'): ?>
                                      <span class="label label-success">Present</span>
                                    <?php else: ?>
                                      <span class="label label-danger">Absent</span>
                                    <?php endif; ?>
                                  </td>
                                </tr>
                              <?php endwhile; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="3" class="text-center no-records">No attendance records found</td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  <?php endwhile; ?>
                <?php else: ?>
                  <div class="alert alert-info">
                    <h4>No students associated with your account.</h4>
                    <p>If you believe this is an error, please contact the school administration.</p>
                  </div>
                <?php endif; ?>
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