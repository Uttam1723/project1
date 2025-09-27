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

// If student is still not found, show error
if (!$student_found) {
    echo "<div class='alert alert-danger'>";
    echo "<h3>Error: Student not found in database</h3>";
    echo "<p>Username: " . htmlspecialchars($username) . "</p>";
    echo "<p>User ID: " . htmlspecialchars($uid) . "</p>";
    echo "<p>Please contact your administrator.</p>";
    echo "</div>";
    
    // Include footer and exit
    include_once 'footer.php';
    exit;
}

// Get student's class
 $class_id = $student_info['classroom'];

// Get class information
 $class_sql = "SELECT * FROM classroom WHERE hno = ?";
 $class_stmt = $conn->prepare($class_sql);
 $class_stmt->bind_param("s", $class_id);
 $class_stmt->execute();
 $class_result = $class_stmt->get_result();

if ($class_result->num_rows > 0) {
    $class_info = $class_result->fetch_assoc();
} else {
    $class_info = ['title' => 'Unknown Class'];
}

// Get timetable for the student's class
 $sql = "SELECT t.*, s.title as subject_title 
        FROM timetable t
        JOIN subject s ON t.subject_id = s.sid
        WHERE t.class_id = ?
        ORDER BY FIELD(t.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), t.start_time";
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("s", $class_id);
 $stmt->execute();
 $result = $stmt->get_result();

 $timetableData = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $timetableData[] = $row;
    }
}

// Get today's day
 $today = date('l');

// Get current time
 $current_time = date('H:i');

// Function to check if a time slot is current
function isCurrentTimeSlot($start, $end, $current_day, $today) {
    if ($current_day != $today) {
        return false;
    }
    
    $current_timestamp = strtotime($current_time);
    $start_timestamp = strtotime($start);
    $end_timestamp = strtotime($end);
    
    return ($current_timestamp >= $start_timestamp && $current_timestamp < $end_timestamp);
}

// Function to check if a time slot is upcoming
function isUpcomingTimeSlot($start, $current_day, $today) {
    if ($current_day != $today) {
        return false;
    }
    
    $current_timestamp = strtotime($current_time);
    $start_timestamp = strtotime($start);
    
    return ($current_timestamp < $start_timestamp);
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Timetable</title>
  <?php include_once 'header.php'; ?>
  <style>
    .student-info-card {
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 25px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .student-info-card h3 {
      margin-top: 0;
      margin-bottom: 15px;
      font-weight: 300;
    }
    .student-details {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 15px;
    }
    .detail-item {
      display: flex;
      align-items: center;
    }
    .detail-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
    }
    .detail-content {
      flex: 1;
    }
    .detail-label {
      font-size: 12px;
      opacity: 0.8;
      margin-bottom: 2px;
    }
    .detail-value {
      font-size: 16px;
      font-weight: 500;
    }
    .timetable-container {
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      padding: 20px;
      margin-bottom: 25px;
    }
    .timetable-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      border-bottom: 1px solid #eee;
      padding-bottom: 15px;
    }
    .timetable-title {
      margin: 0;
      font-size: 22px;
      color: #333;
    }
    .print-btn {
      background: #6a11cb;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 5px;
      cursor: pointer;
    }
    .print-btn:hover {
      background: #5a0dbb;
    }
    .timetable-grid {
      display: grid;
      grid-template-columns: 100px repeat(5, 1fr);
      gap: 1px;
      background-color: #ddd;
      border: 1px solid #ddd;
      border-radius: 4px;
      overflow: hidden;
      margin-bottom: 20px;
    }
    .timetable-header-cell {
      background-color: #f5f5f5;
      padding: 15px 10px;
      text-align: center;
      font-weight: bold;
    }
    .timetable-time-cell {
      background-color: #f5f5f5;
      padding: 15px 10px;
      text-align: center;
      font-weight: bold;
      font-size: 14px;
    }
    .timetable-cell {
      background-color: white;
      padding: 15px 10px;
      min-height: 80px;
      position: relative;
    }
    .time-slot {
      font-size: 12px;
      color: #666;
    }
    .subject-title {
      font-weight: bold;
      margin-bottom: 5px;
      font-size: 16px;
    }
    .time-range {
      font-size: 12px;
      color: #666;
    }
    .today-highlight {
      background-color: #e8f5e9;
    }
    .today-header {
      background-color: #c8e6c9;
    }
    .current-class {
      background-color: #fff3e0;
      border-left: 4px solid #ff9800;
    }
    .upcoming-class {
      background-color: #e3f2fd;
      border-left: 4px solid #2196f3;
    }
    .current-badge {
      position: absolute;
      top: 5px;
      right: 5px;
      background: #ff9800;
      color: white;
      font-size: 10px;
      padding: 2px 6px;
      border-radius: 10px;
    }
    .upcoming-badge {
      position: absolute;
      top: 5px;
      right: 5px;
      background: #2196f3;
      color: white;
      font-size: 10px;
      padding: 2px 6px;
      border-radius: 10px;
    }
    .no-classes {
      text-align: center;
      padding: 30px;
      color: #666;
    }
    @media print {
      .no-print {
        display: none;
      }
      .timetable-container {
        box-shadow: none;
        border: 1px solid #ddd;
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
            <!-- Student Information Card -->
            <div class="student-info-card">
              <h3>My Timetable</h3>
              <div class="student-details">
                <div class="detail-item">
                  <div class="detail-icon">
                    <i class="fa fa-user"></i>
                  </div>
                  <div class="detail-content">
                    <div class="detail-label">Student Name</div>
                    <div class="detail-value"><?php echo htmlspecialchars($student_info['fname'] . ' ' . ($student_info['lname'] ?? '')); ?></div>
                  </div>
                </div>
                
                <div class="detail-item">
                  <div class="detail-icon">
                    <i class="fa fa-id-card"></i>
                  </div>
                  <div class="detail-content">
                    <div class="detail-label">Student ID</div>
                    <div class="detail-value"><?php echo htmlspecialchars($student_id); ?></div>
                  </div>
                </div>
                
                <div class="detail-item">
                  <div class="detail-icon">
                    <i class="fa fa-school"></i>
                  </div>
                  <div class="detail-content">
                    <div class="detail-label">Class</div>
                    <div class="detail-value"><?php echo htmlspecialchars($class_info['title']); ?></div>
                  </div>
                </div>
                
                <div class="detail-item">
                  <div class="detail-icon">
                    <i class="fa fa-calendar-day"></i>
                  </div>
                  <div class="detail-content">
                    <div class="detail-label">Today</div>
                    <div class="detail-value"><?php echo $today; ?></div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Timetable Container -->
            <div class="timetable-container">
              <div class="timetable-header">
                <h3 class="timetable-title">Weekly Timetable</h3>
                <button class="print-btn no-print" onclick="window.print()">
                  <i class="fa fa-print"></i> Print
                </button>
              </div>
              
              <?php if (empty($timetableData)): ?>
                <div class="no-classes">
                  <i class="fa fa-calendar-times fa-3x" style="color: #ccc; margin-bottom: 15px;"></i>
                  <h4>No timetable entries found for your class.</h4>
                  <p>Please contact your administrator if this is an error.</p>
                </div>
              <?php else: ?>
                <div class="timetable-grid">
                  <div class="timetable-header-cell">Time / Day</div>
                  <div class="timetable-header-cell <?php echo ($today == 'Monday') ? 'today-header' : ''; ?>">Monday</div>
                  <div class="timetable-header-cell <?php echo ($today == 'Tuesday') ? 'today-header' : ''; ?>">Tuesday</div>
                  <div class="timetable-header-cell <?php echo ($today == 'Wednesday') ? 'today-header' : ''; ?>">Wednesday</div>
                  <div class="timetable-header-cell <?php echo ($today == 'Thursday') ? 'today-header' : ''; ?>">Thursday</div>
                  <div class="timetable-header-cell <?php echo ($today == 'Friday') ? 'today-header' : ''; ?>">Friday</div>
                  
                  <?php
                  // Get unique time slots
                  $timeSlots = [];
                  foreach ($timetableData as $entry) {
                    $timeSlot = $entry['start_time'] . ' - ' . $entry['end_time'];
                    if (!in_array($timeSlot, $timeSlots)) {
                      $timeSlots[] = $timeSlot;
                    }
                  }
                  sort($timeSlots);
                  
                  foreach ($timeSlots as $timeSlot) {
                    list($start, $end) = explode(' - ', $timeSlot);
                    echo "<div class='timetable-time-cell time-slot'>$start<br>$end</div>";
                    
                    foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day) {
                      // Determine cell class based on day and time
                      $cellClass = 'timetable-cell';
                      if ($day == $today) {
                          $cellClass .= ' today-highlight';
                      }
                      
                      echo "<div class='$cellClass'>";
                      
                      $found = false;
                      foreach ($timetableData as $entry) {
                        if ($entry['day'] == $day && $entry['start_time'] == $start && $entry['end_time'] == $end) {
                          // Check if this is the current class
                          if (isCurrentTimeSlot($start, $end, $day, $today)) {
                              $cellClass .= ' current-class';
                              echo "<div class='current-badge'>NOW</div>";
                          }
                          // Check if this is an upcoming class
                          else if (isUpcomingTimeSlot($start, $day, $today)) {
                              $cellClass .= ' upcoming-class';
                              echo "<div class='upcoming-badge'>NEXT</div>";
                          }
                          
                          echo "<div class='subject-title'>" . htmlspecialchars($entry['subject_title']) . "</div>";
                          echo "<div class='time-range'>" . $entry['start_time'] . " - " . $entry['end_time'] . "</div>";
                          $found = true;
                          break;
                        }
                      }
                      
                      if (!$found) {
                        echo "&nbsp;";
                      }
                      
                      echo "</div>";
                    }
                  }
                  ?>
                </div>
              <?php endif; ?>
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