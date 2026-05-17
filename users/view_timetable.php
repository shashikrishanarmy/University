<?php
// Start session to identify the logged-in student
session_start();
include '../config/db.php';

// Mocking session for testing if your login system isn't active yet.
// REMOVE these two lines once actual login session $_SESSION['user_id'] is set.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; 
}

$student_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>NEXUS UNIVERSITY - My Lecture Timetable</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; font-family: Arial, sans-serif;
            background-color: #7480b5ff; padding-top: 110px;
        }
        nav {
            background: #0b1d51; padding: 15px; display: flex;
            justify-content: space-between; align-items: center;
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        }
        nav a { color: white; text-decoration: none; margin: 10px; }
        nav a:hover { color: gold; }
        .logo-section { display: flex; align-items: center; gap: 10px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }
        
        .title { text-align: center; padding: 20px; color: #0b1d51; }
        .container { width: 85%; margin: 0 auto 60px auto; }
        
        /* Table Container Styles */
        .timetable-box {
            background: white; padding: 20px; border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%; border-collapse: collapse; background: white;
        }
        table th {
            background: #0b1d51; color: white; padding: 15px; text-align: left;
        }
        table td {
            padding: 15px; text-align: left; border-bottom: 1px solid #ddd;
        }
        table tr:hover { background: #f9f9f9; }
        
        /* Badges styling */
        .badge-live {
            background: #28a745; color: white; padding: 5px 8px; 
            border-radius: 4px; font-weight: bold; font-size: 12px;
        }
        .badge-upcoming {
            background: #007bff; color: white; padding: 5px 8px; 
            border-radius: 4px; font-weight: bold; font-size: 12px;
        }
        .no-data {
            text-align: center; padding: 30px; color: #6c757d; font-style: italic;
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo-section">
            <img src="../assets/images/logo.png" alt="Logo" class="logo">
            <h2 style="color:white; margin:0;">NEXUS UNIVERSITY</h2>
        </div>
        <div>
            <a href="dashboard.php">DASHBOARD</a>
            <a href="view_materials.php">MATERIALS</a>
            <a href="view_timetable.php" style="color:gold;">TIMETABLE</a>
            <a href="assignments.php">ASSIGNMENTS</a>
            <a href="../auth/logout.php">LOGOUT</a>
        </div>
    </nav>

    <div class="title">
        <h1>My Lecture Schedule</h1>
    </div>

    <div class="container">
        <div class="timetable-box">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Lecture Topic</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue / Room</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Set timezone to match your system location
                    date_default_timezone_set('Asia/Colombo');
                    $current_time = date('Y-m-d H:i:s');

                    // SQL JOIN: Look up scheduled classes for courses this specific student is enrolled in.
                    // Orders them chronological so the closest lecture shows first.
                    $query = "SELECT t.*, c.course_name 
                              FROM timetables t
                              JOIN courses c ON t.course_id = c.id
                              JOIN enrollments e ON c.id = e.course_id
                              WHERE e.student_id = $student_id AND t.schedule_date_time >= '$current_time'
                              ORDER BY t.schedule_date_time ASC";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $lecture_timestamp = strtotime($row['schedule_date_time']);
                            
                            // Split the full DATETIME into cleaner visual formats
                            $date_display = date('l, M d, Y', $lecture_timestamp);
                            $time_display = date('h:i A', $lecture_timestamp);
                            
                            // Calculate if a class starts within the next hour to label it as "Happening Soon"
                            $time_diff = $lecture_timestamp - time();
                            $is_soon = ($time_diff > 0 && $time_diff <= 3600);
                            ?>
                            
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['course_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['lecture_title']); ?></td>
                                <td><?php echo $date_display; ?></td>
                                <td><?php echo $time_display; ?></td>
                                <td>📍 <?php echo htmlspecialchars($row['room']); ?></td>
                                <td>
                                    <?php if ($is_soon): ?>
                                        <span class="badge-live">Starts Soon</span>
                                    <?php else: ?>
                                        <span class="badge-upcoming">Scheduled</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='no-data'>No upcoming scheduled lectures found for your courses.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>