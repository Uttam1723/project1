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
        ORDER BY t.start_time";
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
function isCurrentTimeSlot($start, $end, $current_day, $today, $current_time) {
    if ($current_day != $today) {
        return false;
    }
    
    $current_timestamp = strtotime($current_time);
    $start_timestamp = strtotime($start);
    $end_timestamp = strtotime($end);
    
    return ($current_timestamp >= $start_timestamp && $current_timestamp < $end_timestamp);
}

// Function to check if a time slot is upcoming
function isUpcomingTimeSlot($start, $current_day, $today, $current_time) {
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
  <title>My Schedule</title>
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
    /* Styles for daily schedule */
    .daily-schedule-list {
        list-style: none;
        padding: 0;
    }
    .daily-schedule-item {
        background: #f9f9f9;
        border-left: 5px solid #6a11cb;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease-in-out;
    }
    .daily-schedule-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .daily-schedule-item.current {
        background: #fff3e0;
        border-left-color: #ff9800;
    }
    .daily-schedule-item.upcoming {
        background: #e3f2fd;
        border-left-color: #2196f3;
    }
    .daily-schedule-time {
        font-weight: bold;
        color: #555;
        font-size: 14px;
    }
    .daily-schedule-subject {
        font-size: 18px;
        font-weight: 500;
    }
    .daily-schedule-badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 12px;
        color: white;
        font-weight: bold;
    }
    .daily-schedule-badge.now {
        background: #ff9800;
    }
    .daily-schedule-badge.next {
        background: #2196f3;
    }
    .no-classes {
      text-align: center;
      padding: 30px;
      color: #666;
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
              <h3>My Schedule for Today</h3>
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

            <!-- Today's Schedule Section -->
            <div class="timetable-container">
                <div class="timetable-header">
                    <h3 class="timetable-title">Today's Classes</h3>
                </div>
                <?php
                // Filter for today's classes
                $todayClasses = array_filter($timetableData, function($entry) use ($today) {
                    return $entry['day'] === $today;
                });

                if (!empty($todayClasses)): ?>
                    <ul class="daily-schedule-list">
                        <?php foreach ($todayClasses as $entry): 
                            // Determine class status
                            $isCurrent = isCurrentTimeSlot($entry['start_time'], $entry['end_time'], $entry['day'], $today, $current_time);
                            $isUpcoming = isUpcomingTimeSlot($entry['start_time'], $entry['day'], $today, $current_time);
                            $itemClass = 'daily-schedule-item';
                            
                            if ($isCurrent) {
                                $itemClass .= ' current';
                            } elseif ($isUpcoming) {
                                $itemClass .= ' upcoming';
                            }
                        ?>
                            <li class="<?php echo $itemClass; ?>">
                                <div>
                                    <div class="daily-schedule-subject"><?php echo htmlspecialchars($entry['subject_title']); ?></div>
                                    <div class="daily-schedule-time"><?php echo date('h:i A', strtotime($entry['start_time'])) . ' - ' . date('h:i A', strtotime($entry['end_time'])); ?></div>
                                </div>
                                <?php if ($isCurrent): ?>
                                    <span class="daily-schedule-badge now">NOW</span>
                                <?php elseif ($isUpcoming): ?>
                                    <span class="daily-schedule-badge next">NEXT</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="no-classes">
                        <i class="fa fa-calendar-times fa-3x" style="color: #ccc; margin-bottom: 15px;"></i>
                        <p>You have no classes scheduled for today.</p>
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