<?php
session_start();
include '../config/db.php';


$student_id = 0;

if (isset($_SESSION['student_id'])) {
    $student_id = intval($_SESSION['student_id']);
} elseif (isset($_SESSION['user_id'])) {
    $student_id = intval($_SESSION['user_id']);
} elseif (isset($_SESSION['id'])) {
    $student_id = intval($_SESSION['id']);
}

// If login failed, redirect to login page
if ($student_id === 0) {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - My Schedule</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #7480b5ff;
            padding-top: 110px;
        }

        nav {
            background: #0b1d51;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 10px;
        }

        nav a:hover {
            color: gold;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .logo-section h2 {
            color: white;
            margin: 0;
        }

        .title {
            text-align: center;
            padding: 20px 20px;
        }

        .title h1 {
            color: #0b1d51;
            font-size: 40px;
            margin: 0 0 10px 0;
        }

        .container {
            width: 80%;
            margin: auto;
            margin-bottom: 60px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        th {
            background: #0b1d51;
            color: white;
            padding: 15px;
        }

        td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table tr:hover {
            background: #f2f2f2;
        }

        .no-records {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            font-weight: bold;
            color: #0b1d51;
            font-size: 18px;
        }

        footer {
            background: #0b1d51;
            color: white;
            padding: 20px;
            margin-top: 40px;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-left p {
            margin: 5px 0;
        }

        .map-container iframe {
            width: 300px;
            height: 200px;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 15px;
            border-top: 1px solid #ffffff33;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    
    <nav>
        <div class="logo-section">
            <img src="../assets/images/logo.png" alt="NEXUS UNIVERSITY Logo" class="logo">
            <h2>NEXUS UNIVERSITY</h2>
        </div>
        <div>
            <a href="../users/home.php" style="color:gold;">STUDENT PANEL</a>
            <a href="../users/view_materials.php">MATERIALS</a>
            <a href="../users/assignments.php">ASSIGNMENTS</a>
            <a href="../users/view_timetable.php">TIMETABLE</a>
            <a href="../auth/logout.php">LOGOUT</a>
        </div>
    </nav>

    
    <section class="title">
        <h1>My Lecture Timetable</h1>
        <p style="color: #0b1d51; font-weight: bold;">Showing upcoming sessions based on your registered courses</p>
    </section>

    <div class="container">

        <?php
        // Database join query 
        $query = "SELECT t.lecture_title, t.schedule_date_time, t.room, c.course_name 
                  FROM timetables t
                  INNER JOIN enrollments e ON t.course_id = e.course_id
                  INNER JOIN courses c ON t.course_id = c.id
                  WHERE e.student_id = $student_id 
                    AND t.schedule_date_time >= NOW()
                  ORDER BY t.schedule_date_time ASC";

        $result = mysqli_query($conn, $query);

        if($result && mysqli_num_rows($result) > 0) {
        ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Course Module</th>
                        <th>Lecture Session Title</th>
                        <th>Scheduled Date & Time</th>
                        <th>Room Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['course_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['lecture_title']); ?></td>
                        <td>
                            <?php 
                            // timestamp
                            echo date('D, M d, Y - h:i A', strtotime($row['schedule_date_time'])); 
                            ?>
                        </td>
                        <td>
                            <span style="background: #eef0f8; padding: 4px 10px; border-radius: 4px; border: 1px solid #ccc; font-weight: bold;">
                                <?php echo htmlspecialchars($row['room']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php 
        } else { 
            //else part for no records
            echo "<div class='no-records'>📅 No upcoming lectures scheduled for your enrolled courses right now.</div>";
        } 
        ?>

    </div>

    
    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <h3>Contact Us</h3>
                <p>📞 Call: 0114325690</p>
                <p>✉️ Email: nexusuniversity@gmail.com</p>
                <p>📍 Address: Nexus University, New Kandy Road, Malabe</p>
            </div>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=..." style="border:0;" loading="lazy"></iframe>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>
        </div>
    </footer>

</body>
</html>