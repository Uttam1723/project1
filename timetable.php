<?php
session_start();
include_once 'database.php';

// Check if user is a teacher
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Teacher') {
  header('Location: ./logout.php');
}

 $message = '';
 $timetableData = [];
 $classSelected = false;
 $selectedClass = '';

// Handle PDF generation
if (isset($_GET['action']) && $_GET['action'] == 'generate_pdf' && isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];
    
    // Get class information
    $class_sql = "SELECT * FROM classroom WHERE hno = '$class_id'";
    $class_result = $conn->query($class_sql);
    $class_data = $class_result->fetch_assoc();
    
    // Get timetable data for the selected class
    $sql = "SELECT t.day, t.start_time, t.end_time, s.title as subject_name, c.title as class_name
            FROM timetable t
            JOIN subject s ON t.subject_id = s.sid
            JOIN classroom c ON t.class_id = c.hno
            WHERE t.class_id = '$class_id'
            ORDER BY FIELD(t.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), t.start_time";
    $result = $conn->query($sql);
    
    $timetableData = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $timetableData[] = $row;
        }
    }
    
    // If no timetable data found
    if (empty($timetableData)) {
        echo "<div class='alert alert-warning'>No timetable found for this class</div>";
        exit;
    }
    
    // Create time slots (8:00 AM to 5:00 PM)
    $timeSlots = [];
    for ($hour = 8; $hour <= 17; $hour++) {
        for ($minute = 0; $minute < 60; $minute += 30) {
            $timeSlots[] = sprintf('%02d:%02d', $hour, $minute);
        }
    }
    
    // Days of the week
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    
    // Create timetable grid
    $timetableGrid = [];
    
    // Initialize grid with empty cells
    foreach ($timeSlots as $timeSlot) {
        $timetableGrid[$timeSlot] = [];
        foreach ($days as $day) {
            $timetableGrid[$timeSlot][$day] = '';
        }
    }
    
    // Fill grid with timetable data
    foreach ($timetableData as $entry) {
        $day = $entry['day'];
        $start_time = $entry['start_time'];
        $end_time = $entry['end_time'];
        $subject = $entry['subject_name'];
        
        // Find the time slots that fall within the class time
        foreach ($timeSlots as $timeSlot) {
            if ($timeSlot >= $start_time && $timeSlot < $end_time) {
                $timetableGrid[$timeSlot][$day] = $subject;
            }
        }
    }
    
    // Output HTML content for printing
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Class Timetable - <?php echo $class_data['title']; ?></title>
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
            .timetable-title {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 10px;
            }
            .class-name {
                font-size: 18px;
                margin-bottom: 20px;
            }
            .timetable-grid {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 20px;
            }
            .timetable-grid th, .timetable-grid td {
                border: 1px solid #000;
                padding: 8px;
                text-align: center;
            }
            .timetable-grid th {
                background-color: #f2f2f2;
                font-weight: bold;
            }
            .timetable-grid .time-slot {
                background-color: #f2f2f2;
                font-weight: bold;
                width: 100px;
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
            <div class="timetable-title">Class Timetable</div>
            <div class="class-name"><?php echo $class_data['title']; ?></div>
        </div>
        
        <table class="timetable-grid">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($timeSlots as $timeSlot): ?>
                    <?php
                    $hasData = false;
                    foreach ($days as $day) {
                        if (!empty($timetableGrid[$timeSlot][$day])) {
                            $hasData = true;
                            break;
                        }
                    }
                    
                    if ($hasData):
                    ?>
                    <tr>
                        <td class="time-slot"><?php echo date('h:i A', strtotime($timeSlot)); ?></td>
                        <?php foreach ($days as $day): ?>
                            <td><?php echo $timetableGrid[$timeSlot][$day]; ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="footer">
            Generated on <?php echo date('F j, Y'); ?>
        </div>
        
        <div class="print-button">
            <button onclick="window.print()">Print Timetable</button>
            <button onclick="window.close()">Close Window</button>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Handle class selection
if (isset($_POST['select_class'])) {
  $selectedClass = $_POST['class_id'];
  $classSelected = true;
  
  // Get existing timetable for the selected class
  $sql = "SELECT t.*, c.title as class_title, s.title as subject_title 
          FROM timetable t
          JOIN classroom c ON t.class_id = c.hno
          JOIN subject s ON t.subject_id = s.sid
          WHERE t.class_id = '$selectedClass'
          ORDER BY FIELD(t.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), t.start_time";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $timetableData[] = $row;
    }
  }
}

// Handle adding new timetable entry
if (isset($_POST['add_timetable'])) {
  $class_id = $_POST['class_id'];
  $subject_id = $_POST['subject_id'];
  $day = $_POST['day'];
  $start_time = $_POST['start_time'];
  $end_time = $_POST['end_time'];
  $teacher_id = $_SESSION['user']; // Using teacher's username as ID
  
  // Check for conflicts
  $conflict_sql = "SELECT * FROM timetable 
                   WHERE class_id = '$class_id' 
                   AND day = '$day' 
                   AND (
                     (start_time <= '$start_time' AND end_time > '$start_time') OR
                     (start_time < '$end_time' AND end_time >= '$end_time') OR
                     (start_time >= '$start_time' AND end_time <= '$end_time')
                   )";
  $conflict_result = $conn->query($conflict_sql);
  
  if ($conflict_result->num_rows > 0) {
    $message = "Time conflict detected! Please choose a different time slot.";
  } else {
    $sql = "INSERT INTO timetable (class_id, subject_id, day, start_time, end_time, teacher_id) 
            VALUES ('$class_id', '$subject_id', '$day', '$start_time', '$end_time', '$teacher_id')";
    
    if ($conn->query($sql) === TRUE) {
      $message = "Timetable entry added successfully!";
      // Refresh the timetable data
      $selectedClass = $class_id;
      $classSelected = true;
      
      $sql = "SELECT t.*, c.title as class_title, s.title as subject_title 
              FROM timetable t
              JOIN classroom c ON t.class_id = c.hno
              JOIN subject s ON t.subject_id = s.sid
              WHERE t.class_id = '$selectedClass'
              ORDER BY FIELD(t.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), t.start_time";
      $result = $conn->query($sql);
      
      $timetableData = [];
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $timetableData[] = $row;
        }
      }
    } else {
      $message = "Error adding timetable entry: " . $conn->error;
    }
  }
}

// Handle deleting timetable entry
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $sql = "DELETE FROM timetable WHERE id = '$id'";
  
  if ($conn->query($sql) === TRUE) {
    $message = "Timetable entry deleted successfully!";
    // Refresh the timetable data
    if ($classSelected) {
      $sql = "SELECT t.*, c.title as class_title, s.title as subject_title 
              FROM timetable t
              JOIN classroom c ON t.class_id = c.hno
              JOIN subject s ON t.subject_id = s.sid
              WHERE t.class_id = '$selectedClass'
              ORDER BY FIELD(t.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), t.start_time";
      $result = $conn->query($sql);
      
      $timetableData = [];
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $timetableData[] = $row;
        }
      }
    }
  } else {
    $message = "Error deleting timetable entry: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Timetable Management</title>
  <?php include_once 'header.php'; ?>
  <style>
    .timetable-grid {
      display: grid;
      grid-template-columns: 100px repeat(5, 1fr);
      gap: 1px;
      background-color: #ddd;
      border: 1px solid #ddd;
      border-radius: 4px;
      overflow: hidden;
    }
    .timetable-header {
      background-color: #f5f5f5;
      padding: 10px;
      text-align: center;
      font-weight: bold;
    }
    .timetable-time {
      background-color: #f5f5f5;
      padding: 10px;
      text-align: center;
      font-weight: bold;
    }
    .timetable-cell {
      background-color: white;
      padding: 10px;
      min-height: 60px;
    }
    .time-slot {
      font-size: 12px;
      color: #666;
    }
    .subject-title {
      font-weight: bold;
      margin-bottom: 5px;
    }
    .class-info {
      font-size: 12px;
      color: #666;
    }
    .delete-btn {
      margin-top: 5px;
    }
    .pdf-btn {
      margin-bottom: 15px;
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
                <h2>Timetable Management</h2>
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
                    <button type="submit" name="select_class" class="btn btn-primary">Manage Timetable</button>
                  </form>
                <?php else: ?>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="x_panel">
                        <div class="x_title">
                          <h3>Add Timetable Entry</h3>
                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                          <form method="post" action="">
                            <input type="hidden" name="class_id" value="<?php echo $selectedClass; ?>">
                            
                            <div class="form-group">
                              <label for="subject_id">Subject:</label>
                              <select name="subject_id" class="form-control" required>
                                <option value="">-- Select Subject --</option>
                                <?php
                                $sql = "SELECT * FROM subject";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                  while($row = $result->fetch_assoc()) {
                                    echo "<option value='".$row["sid"]."'>".$row["title"]."</option>";
                                  }
                                }
                                ?>
                              </select>
                            </div>
                            
                            <div class="form-group">
                              <label for="day">Day:</label>
                              <select name="day" class="form-control" required>
                                <option value="">-- Select Day --</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                              </select>
                            </div>
                            
                            <div class="form-group">
                              <label for="start_time">Start Time:</label>
                              <input type="time" name="start_time" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                              <label for="end_time">End Time:</label>
                              <input type="time" name="end_time" class="form-control" required>
                            </div>
                            
                            <button type="submit" name="add_timetable" class="btn btn-primary">Add Entry</button>
                            <a href="timetable.php" class="btn btn-default">Select Different Class</a>
                          </form>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-8">
                      <div class="x_panel">
                        <div class="x_title">
                          <h3>Timetable for <?php 
                            $sql = "SELECT title FROM classroom WHERE hno = '$selectedClass'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['title'];
                          ?></h3>
                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                          <?php if (!empty($timetableData)): ?>
                            <div class="pdf-btn">
                              <a href="timetable.php?action=generate_pdf&class_id=<?php echo $selectedClass; ?>" class="btn btn-primary" target="_blank">
                                <i class="fa fa-file-pdf-o"></i> Generate PDF
                              </a>
                            </div>
                          <?php endif; ?>
                          
                          <?php if (empty($timetableData)): ?>
                            <div class="alert alert-warning">No timetable entries found for this class.</div>
                          <?php else: ?>
                            <div class="timetable-grid">
                              <div class="timetable-header">Time / Day</div>
                              <div class="timetable-header">Monday</div>
                              <div class="timetable-header">Tuesday</div>
                              <div class="timetable-header">Wednesday</div>
                              <div class="timetable-header">Thursday</div>
                              <div class="timetable-header">Friday</div>
                              
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
                                echo "<div class='timetable-time time-slot'>$start<br>$end</div>";
                                
                                foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day) {
                                  echo "<div class='timetable-cell'>";
                                  
                                  $found = false;
                                  foreach ($timetableData as $entry) {
                                    if ($entry['day'] == $day && $entry['start_time'] == $start && $entry['end_time'] == $end) {
                                      echo "<div class='subject-title'>" . $entry['subject_title'] . "</div>";
                                      echo "<div class='class-info'>" . $entry['class_title'] . "</div>";
                                      echo "<a href='timetable.php?delete=" . $entry['id'] . "' class='btn btn-xs btn-danger delete-btn' onclick='return confirm(\"Are you sure you want to delete this entry?\")'>Delete</a>";
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