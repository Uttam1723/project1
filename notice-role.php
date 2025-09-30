<?php session_start();
include_once 'database.php';
if (!isset($_SESSION['user'])||$_SESSION['role']=='Teacher') {
  header('Location:./logout.php');
}

// Handle delete functionality (only for admins)
if (isset($_GET['delete']) && $_SESSION['role'] == 'Admin') {
  $sql = "DELETE FROM notice WHERE id='".$_GET['delete']."'";
  $conn->query($sql);
  header('Location: '.$_SERVER['PHP_SELF']);
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Notice Board | Student Dashboard</title>
  <link rel="icon" href="../img/favicon2.png">
  <?php include_once 'header.php'; ?>
  
  <style>
    body {
      background-color: #f8f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .dashboard-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
      border-radius: 0 0 20px 20px;
    }
    
    .dashboard-header h1 {
      font-weight: 300;
      margin-bottom: 0.5rem;
    }
    
    .dashboard-header p {
      opacity: 0.8;
      margin-bottom: 0;
    }
    
    .notice-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      padding: 2rem;
      margin-bottom: 2rem;
    }
    
    .notice-header {
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #eee;
    }
    
    .notice-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #333;
    }
    
    .notice-card {
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      border-left: 4px solid #667eea;
    }
    
    .notice-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .notice-header-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }
    
    .notice-id {
      font-size: 0.9rem;
      color: #999;
    }
    
    .notice-date {
      font-size: 0.9rem;
      color: #999;
      display: flex;
      align-items: center;
    }
    
    .notice-date i {
      margin-right: 0.5rem;
    }
    
    .notice-content {
      margin-bottom: 1rem;
      line-height: 1.8;
      font-size: 1.2rem;
      color: #333;
      font-weight: 400;
    }
    
    .notice-actions {
      display: flex;
      justify-content: flex-end;
    }
    
    .notice-btn {
      padding: 0.4rem 0.8rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 0.9rem;
      transition: all 0.2s ease;
      background: #f8d7da;
      color: #721c24;
    }
    
    .notice-btn:hover {
      background: #f5c6cb;
    }
    
    .no-notices {
      text-align: center;
      padding: 3rem;
      color: #666;
    }
    
    .no-notices i {
      font-size: 4rem;
      margin-bottom: 1rem;
      color: #ddd;
    }
    
    .no-notices h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    
    .no-notices p {
      font-size: 1.1rem;
    }
    
    @media (max-width: 768px) {
      .notice-header-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
      }
      
      .notice-content {
        font-size: 1.1rem;
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
        <div class="dashboard-header">
          <div class="container-fluid">
            <h1>Notice Board</h1>
            <p>Stay updated with the latest announcements</p>
          </div>
        </div>
        
        <div class="container-fluid">
          <div class="notice-container">
            <div class="notice-header">
              <h2 class="notice-title">Latest Notices</h2>
            </div>
            
            <?php
            // Get notices from database
            $sql = "SELECT * FROM notice WHERE odience='".$_SESSION['role']."' OR odience='All' ORDER BY date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              // Display notices
              while($row = $result->fetch_assoc()) {
                $noticeId = $row["id"];
                $noticeContent = $row["notice"];
                $noticeDate = $row["date"];
                
                // Format date
                $formattedDate = date("M d, Y h:i A", strtotime($noticeDate));
              ?>
                <div class="notice-card">
                  <div class="notice-header-info">
                    <div>
                      <span class="notice-id">Notice #<?php echo $noticeId; ?></span>
                    </div>
                    <div class="notice-date">
                      <i class="fa fa-calendar"></i> <?php echo $formattedDate; ?>
                    </div>
                  </div>
                  <div class="notice-content">
                    <?php echo nl2br(htmlspecialchars($noticeContent)); ?>
                  </div>
                  <div class="notice-actions">
                    <?php if ($_SESSION['role'] == 'Admin') { ?>
                      <a href="?delete=<?php echo $noticeId; ?>" class="notice-btn" onclick="return confirm('Are you sure you want to delete this notice?')">
                        <i class="fa fa-trash"></i> Delete
                      </a>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
            <?php } else { ?>
              <div class="no-notices">
                <i class="fa fa-bullhorn"></i>
                <h3>No Notices Available</h3>
                <p>There are no notices at the moment. Check back later for updates.</p>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <?php include_once 'footer.php'; ?>
    </div>
  </div>
</body>
</html>