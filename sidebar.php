
       <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="index.php" class="site_title"><i class="fa fa-paw"></i> <span>Student Management System</span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <div class="profile clearfix">
            <div class="profile_pic">
              <img src="images/user.png" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2><?php echo $_SESSION['user']; ?></h2>
            </div>
          </div>
          <!-- /menu profile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <h3>General</h3>
              <ul class="nav side-menu">
                <li id="new"><a href="./index.php"><i class="fa fa-home"></i> <span>Dashboard</span> </a></li>
                <li><a><i class="fa fa-windows"></i> General <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">

                   <?php if($_SESSION['role']=='Teacher'){ ?>

                
                <li id="subject"><a href="./subject.php"><i class="fa fa-book"></i> <span>Subject</span> </a></li>
                <li id="class"><a href="./class.php"><i class="fa fa-bank"></i> <span>Class Room</span> </a></li>
                <li id="attendance"><a href="./attendance1.php"><i class="fa  fa-check"></i> <span>Attendance</span> </a></li>
                <li id="exam"><a href="./exam.php"><i class="fa fa-line-chart"></i> <span>Exam</span> </a></li>
                <li id="examresults"><a href="./examresults.php"><i class="fa fa-graduation-cap"></i> <span>Exam Results</span> </a></li>
                <li id="notice"><a href="./notice.php"><i class="fa fa-envelope-o"></i> <span>Notice</span> </a></li>
               
                <li id="timetable"><a href="./timetable.php"><i class="fa fa-calendar"></i> <span>Timetable</span></a></li>

            <?php } elseif ($_SESSION['role']=='Parent') { ?>
                <li id="student-par"><a href="./student-par.php"><i class="fa fa-users"></i> <span>My child</span> </a></li>
                <li id="notice-role"><a href="./notice-role.php"><i class="fa fa-envelope-o"></i> <span>Notice</span> </a></li>
                <li id="examresults-par"><a href="./examresults-par.php"><i class="fa fa-graduation-cap"></i> <span>Exam Results</span> </a></li>
                <li id="attendance-par"><a href="./parent-attendance.php"><i class="fa fa-check"></i> <span>Child Attendance</span></a></li>
                

            <?php } elseif ($_SESSION['role']=='Student') { ?>
                <li id="notice-role"><a href="./notice-role.php"><i class="fa fa-envelope-o"></i> <span>Notice</span> </a></li>
                <li id="examresults-stu"><a href="./examresults-stu.php"><i class="fa fa-graduation-cap"></i> <span>Exam Results</span> </a></li>
                <li id="attendance-stu"><a href="./student-attendance.php"><i class="fa fa-check"></i> <span>My Attendance</span></a></li>
                <li id="timetable-stu"><a href="./student-timetable.php"><i class="fa fa-calendar"></i> <span>My Timetable</span></a></li>
                

            <?php } elseif ($_SESSION['role']=='Admin') { ?>
                <li id="dashboard-admin"><a href="./teacher.php"><i class="fa fa-dashboard"></i> <span>Add teacher</span></a></li>
                <li id="parent"><a href="./parent.php"><i class="fa  fa-female"></i> <span>Add Parents</span> </a></li>
                <li id="new"><a href="./student.php"><i class="fa fa-users"></i> <span>Add Student</span> </a></li>
                <li id="user"><a href="./user.php"><i class="fa fa-user-plus"></i> <span>Users</span> </a></li>
            
            <?php } ?>


                </ul>
              </li>

            </ul>
          </div>
          <div class="menu_section">
            <h3>User</h3>
            <ul class="nav side-menu">
              <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
          </div>

        </div>
        <!-- /sidebar menu -->

      </div>