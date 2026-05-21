<?php
session_start();
include '../config/db.php';

// Check the authentication
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Submission Tracking</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #7480b5ff;
            padding-top: 100px;
            
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        
        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h1 {
            color: #0b1d51;
            font-size: 36px;
        }

        
        .container {
            width: 85%;
            margin: auto;
            margin-bottom: 50px;
            flex: 1; 
        }

        
        .course-group-header {
            background: #0b1d51;
            color: gold;
            padding: 12px 20px;
            margin-top: 30px;
            border-radius: 6px 6px 0 0;
            font-size: 20px;
            font-weight: bold;
            box-shadow: 0px 4px 6px rgba(0,0,0,0.1);
        }

        .assignment-card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 0 0 6px 6px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        .assignment-meta {
            background: #f8f9fa;
            padding: 12px;
            border-left: 4px solid #0b1d51;
            margin-bottom: 20px;
            border-radius: 0 4px 4px 0;
        }

        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 10px;
        }

        table th {
            background: #343a40;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        table td {
            padding: 12px;
            border: 1px solid #dee2e6;
            font-size: 14px;
        }

        table tr:hover {
            background: #f1f3f5;
        }

        
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
        }

        .badge-submitted {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .btn-view {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 12px;
        }
        
        .btn-view:hover {
            background: #0056b3;
        }

        
        footer {
            background: #0b1d51;
            color: white;
            padding: 20px;
            margin-top: auto; 
            width: 100%;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 85%;
            margin: auto;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-left p {
            margin: 5px 0;
        }

        .map-container iframe {
            width: 300px;
            height: 200px;
            border: 0;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 15px;
            border-top: 1px solid #ffffff33;
            padding-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>


<nav>
    <div class="logo-section">
        <img src="../assets/images/logo.png" class="logo" alt="Logo">
        <h2 style="color:white; margin:0;">NEXUS UNIVERSITY</h2>
    </div>
    <div>
            <a href="../index.php">HOME</a>
            <a href="../admin/coursesdash.php">COURSES</a>
            <a href="../admin/manage_enrollments.php">ENROLLMENTS</a>
            <a href="../admin/manage_materials.php">MATERIALS</a>
            <a href="../admin/view_submissions.php">SUBMISSIONS</a>
            <a href="../admin/manage_assignments.php">ASSIGNMENTS</a>
            <a href="../auth/logout.php">LOGOUT</a> 
    </div>
</nav>


<div class="title">
    <h1>Student Submission Tracking Report</h1>
</div>


<div class="container">

    <?php
    //to check if there are any courses with assignments
    $course_sql = "SELECT DISTINCT c.id, c.course_name 
                   FROM courses c 
                   JOIN assignments a ON c.id = a.course_id 
                   ORDER BY c.course_name ASC";
    $course_result = mysqli_query($conn, $course_sql);

    if (mysqli_num_rows($course_result) > 0) {
        while ($course = mysqli_fetch_assoc($course_result)) {
            $course_id = intval($course['id']);
            
            
            echo "<div class='course-group-header'>" . htmlspecialchars($course['course_name']) . "</div>";

            // STEP 2: Query all specific assignments within this targeted course
            $assign_sql = "SELECT id, title, description, deadline FROM assignments WHERE course_id = $course_id ORDER BY deadline ASC";
            $assign_result = mysqli_query($conn, $assign_sql);

            while ($assign = mysqli_fetch_assoc($assign_result)) {
                $assignment_id = intval($assign['id']);
                ?>
                
                <div class="assignment-card">
                    <div class="assignment-meta">
                        <h3 style="margin: 0 0 5px 0; color: #0b1d51;"><?php echo htmlspecialchars($assign['title']); ?></h3>
                        <p style="margin: 0; font-size: 14px; color: #555;"><?php echo htmlspecialchars($assign['description']); ?></p>
                        <small><b>Deadline cutoff:</b> <?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($assign['deadline']))); ?></small>
                    </div>

                    <!-- SUBMISSION DATA METRIC TABLE -->
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 10%;">Student ID</th>
                                <th style="width: 30%;">Student Name</th>
                                <th style="width: 25%;">Status Indicator</th>
                                <th style="width: 20%;">Date Transmitted</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // STEP 3: The Cross-Reference Query (FIXED: Swapped u.username out for u.name)
                            $status_sql = "SELECT 
                                                u.id AS student_num,
                                                u.name AS student_name,
                                                s.file_path,
                                                s.submitted_at
                                           FROM enrollments e
                                           JOIN users u ON e.student_id = u.id
                                           LEFT JOIN submissions s ON s.assignment_id = $assignment_id AND s.user_id = u.id
                                           WHERE e.course_id = $course_id
                                           ORDER BY u.name ASC";

                            $status_result = mysqli_query($conn, $status_sql);

                            if (mysqli_num_rows($status_result) > 0) {
                                while ($student = mysqli_fetch_assoc($status_result)) {
                                    $is_submitted = !empty($student['file_path']);
                                    ?>
                                    <tr>
                                        <td><code>#<?php echo htmlspecialchars($student['student_num']); ?></code></td>
                                        <td><strong><?php echo htmlspecialchars($student['student_name']); ?></strong></td>
                                        <td>
                                            <?php if ($is_submitted): ?>
                                                <span class="badge badge-submitted">✅ Submitted</span>
                                            <?php else: ?>
                                                <span class="badge badge-pending">⏳ Not Submitted</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                                echo $is_submitted 
                                                    ? htmlspecialchars(date('M d, Y h:i A', strtotime($student['submitted_at']))) 
                                                    : "<span style='color:#bbb;'>—</span>"; 
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($is_submitted): ?>
                                                <a href="<?php echo htmlspecialchars($student['file_path']); ?>" class="btn-view" download>Download File</a>
                                            <?php else: ?>
                                                <span style="color: #999; font-size: 13px; font-style: italic;">No Action</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align:center; color:#777;'>No students are currently enrolled in this course.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <?php
            }
        }
    } else {
        echo "<div class='assignment-card' style='text-align:center;'><p style='margin:0; font-size:16px;'>No active course assignment tracking dependencies found in database.</p></div>";
    }
    ?>

</div>

<!-- UNIVERSITY GLOBAL FOOTER -->
<footer>
    <div class="footer-container">
        <div class="footer-left">
            <h3 style="margin-top: 0; color: gold;">Contact Us</h3>
            <p>📞 Call: 0114325690</p>
            <p>✉️ Email: nexusuniversity@gmail.com</p>
            <p>📍 Address: Nexus University, New Kandy Road, Malabe</p>
        </div>
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=..." loading="lazy" aria-hidden="false"></iframe>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>
    </div>
</footer>

</body>
</html>