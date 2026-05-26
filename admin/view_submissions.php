<?php
// Start session at the very top to preserve feedback message states across header redirections
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/db.php';

// Initialize session flash framework variables
$message = "";

/* ================= UPDATE / ASSIGN GRADE LOGIC ================= */
if (isset($_POST['update_grade'])) {
    $sub_id = intval($_POST['submission_id']);
    $grade = mysqli_real_escape_string($conn, trim($_POST['grade']));

    if ($sub_id > 0) {
        $grade_query = "UPDATE submissions SET grade = '$grade' WHERE id = $sub_id";
        if (mysqli_query($conn, $grade_query)) {
            $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-success'>Grade processed successfully!</div>";
        } else {
            $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Database Error: Failed to save grade information.</div>";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* ================= DELETE ASSIGNMENT ================= */
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $file_query = "SELECT file_path FROM assignments WHERE id = $delete_id";
    $file_result = mysqli_query($conn, $file_query);

    if ($file_row = mysqli_fetch_assoc($file_result)) {
        $physical_file = $file_row['file_path'];
        if (!empty($physical_file) && file_exists($physical_file)) {
            unlink($physical_file);
        }
    }

    $delete_query = "DELETE FROM assignments WHERE id = $delete_id";

    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-success'>Assignment deleted successfully.</div>";
    } else {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Error deleting assignment record.</div>";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Consume current session message to clear it for the next execution run
if (isset($_SESSION['alert_msg'])) {
    $message = $_SESSION['alert_msg'];
    unset($_SESSION['alert_msg']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Submissions</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #7480b5ff;
            padding-top: 110px;
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
            top: 0; left: 0; right: 0;
            z-index: 1000;
        }

        nav a { color: white; text-decoration: none; margin: 10px; }
        nav a:hover { color: gold; }

        .logo-section { display: flex; align-items: center; gap: 10px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }
        .logo-section h2 { color: white; margin: 0; }

        .title { text-align: center; padding: 10px 20px; }
        .title h1 { color: #0b1d51; font-size: 36px; margin-bottom: 5px; }

        /* DATATABLE LAYOUT PACKAGING */
        .table-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 60px;
            flex: 1;
            width: 95%;
            margin-left: auto;
            margin-right: auto;
        }

        .action-bar {
            width: 100%;
            max-width: 1300px;
            display: flex;
            justify-content: flex-start;
            margin-bottom: 15px;
        }

        .btn-track {
            background: #0b1d51;
            color: gold;
            padding: 10px 18px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
        }
        .btn-track:hover { background: white; color: #0b1d51; }

        /* SMOOTH FLASH NOTIFICATION WRAPPER */
        .msg-box {
            width: 100%;
            max-width: 1300px;
            text-align: center;
            margin-bottom: 10px;
        }
        .alert-box {
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* COMPACT STRUCTURAL DATA DESIGN MATRIX */
        table {
            width: 100%;
            max-width: 1300px;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            table-layout: fixed; /* Enforces absolute restriction control against long content lines */
            word-wrap: break-word;
        }

        table th {
            background: #0b1d51;
            color: white;
            padding: 10px;
            font-size: 13px;
        }

        table td {
            padding: 8px 10px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 13px;
            vertical-align: middle;
        }
        table tr:hover { background: #f2f2f2; }

        /* Exact explicit column distribution matrix */
        table th:nth-child(1), table td:nth-child(1) { width: 12%; } /* Course */
        table th:nth-child(2), table td:nth-child(2) { width: 15%; } /* Assignment */
        table th:nth-child(3), table td:nth-child(3) { width: 8%; }  /* Student ID */
        table th:nth-child(4), table td:nth-child(4) { width: 12%; } /* Student Name */
        table th:nth-child(5), table td:nth-child(5) { width: 10%; } /* Submission File */
        table th:nth-child(6), table td:nth-child(6) { width: 13%; } /* Submitted At */
        table th:nth-child(7), table td:nth-child(7) { width: 18%; } /* Grading Input Field */
        table th:nth-child(8), table td:nth-child(8) { width: 12%; } /* Actions */

        /* INLINE GRADING COMPONENT ELEMENT PARADIGMS */
        .grade-form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin: 0;
        }
        .grade-input {
            width: 70px;
            padding: 5px;
            font-size: 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
            text-align: center;
        }
        .btn-grade {
            background: #0b1d51;
            color: white;
            border: none;
            padding: 5px 8px;
            font-size: 11px;
            border-radius: 3px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }
        .btn-grade:hover { background: gold; color: #0b1d51; }

        .btn {
            padding: 5px 8px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
            margin: 2px;
        }
        .delete { background: #dc3545; }
        .delete:hover { background: #bd2130; }
        .download { background: #28a745; }
        .download:hover { background: #218838; }

        footer { background: #0b1d51; color: white; padding: 20px; margin-top: auto; }
        .footer-container { display: flex; justify-content: space-between; align-items: center; }
        .footer-left p { margin: 5px 0; }
        .map-container iframe { width: 300px; height: 200px; }
        .footer-bottom { text-align: center; margin-top: 15px; border-top: 1px solid #ffffff33; padding-top: 10px; }
    </style>
</head>
<body>

    <nav>
        <div class="logo-section">
            <img src="../assets/images/logo.png" alt="NEXUS UNIVERSITY Logo" class="logo">
            <h2>NEXUS UNIVERSITY</h2>
        </div>
        <div>
            <a href="../index.php">HOME</a>
            <a href="../admin/dashboard.php">ADMIN PANEL</a>
            <a href="../admin/coursesdash.php">COURSES</a>
            <a href="../admin/manage_enrollments.php">ENROLLMENTS</a>
            <a href="../admin/manage_materials.php">MATERIALS</a>
            <a href="../admin/view_submissions.php">SUBMISSIONS</a>
            <a href="../admin/manage_assignments.php">ASSIGNMENTS</a>
            <a href="../admin/manage_timetable.php">TIMETABLE</a>
            <a href="../auth/logout.php">LOGOUT</a> 
        </div>
    </nav>

    <section class="title">
        <h1>Student Submissions</h1>
    </section>

    <div class="table-container">
        
        <div class="msg-box"><?php echo $message; ?></div>

        <div class="action-bar">
            <a href="../admin/assignment_submission_report.php" class="btn-track">
                📊 View Submission Tracking Report (Submitted vs Not Submitted)
            </a>
        </div>

        <?php
        /* ================= MAIN SQL QUERY WITH SUBMISSIONS ID EXTRACTION ================= */
        $query = "
        SELECT 
        a.id AS assignment_id,
        a.title,
        a.description,
        a.deadline,
        a.file_path AS assignment_file,
        c.course_name,
        s.id AS submission_id,
        s.file_path AS submission_file,
        s.submitted_at,
        s.grade,
        u.id AS student_num,
        u.name AS student_name
        FROM assignments a
        LEFT JOIN courses c ON a.course_id = c.id
        LEFT JOIN submissions s ON a.id = s.assignment_id
        LEFT JOIN users u ON s.user_id = u.id
        ORDER BY a.id DESC, s.submitted_at DESC
        ";

        $result = mysqli_query($conn, $query);
        ?>

        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Assignment</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Submission</th>
                    <th>Submitted At</th>
                    <th>Grading Center</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { 
                    $has_submission = !empty($row['submission_id']);
            ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($row['course_name'] ?? 'N/A'); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    
                    <td><?php echo $has_submission ? htmlspecialchars($row['student_num']) : '<span style="color:#777; font-style:italic;">N/A</span>'; ?></td>
                    <td><?php echo $has_submission ? htmlspecialchars($row['student_name']) : '<span style="color:#777; font-style:italic;">No Submission</span>'; ?></td>
                    
                    <td>
                        <?php if ($has_submission && !empty($row['submission_file'])) { ?>
                            <a class="btn download" href="<?php echo htmlspecialchars($row['submission_file']); ?>" target="_blank">View File</a>
                        <?php } else { echo "<span style='color:#dc3545;'>❌ Empty</span>"; } ?>
                    </td>
                    <td>
                        <?php echo $has_submission ? htmlspecialchars(date('M d, Y h:i A', strtotime($row['submitted_at']))) : '-'; ?>
                    </td>
                    
                    <td>
                        <?php if ($has_submission) { 
                            // Determine if a valid grade string/int or zero score already lives in the database
                            $has_grade = !empty($row['grade']) || $row['grade'] === '0';
                        ?>
                            <form method="POST" class="grade-form">
                                <input type="hidden" name="submission_id" value="<?php echo $row['submission_id']; ?>">
                                <input type="text" name="grade" class="grade-input" 
                                       placeholder="e.g. A+, 85" 
                                       value="<?php echo htmlspecialchars($row['grade'] ?? ''); ?>">
                                
                                <?php if ($has_grade): ?>
                                    <button type="submit" name="update_grade" class="btn-grade" style="background-color: #2874A6;">Update</button>
                                <?php else: ?>
                                    <button type="submit" name="update_grade" class="btn-grade">Save</button>
                                <?php endif; ?>
                            </form>
                        <?php } else { ?>
                            <span style="color: #999; font-size: 11px; font-style: italic;">Cannot Grade</span>
                        <?php } ?>
                    </td>
                    
                    <td>
                        <?php if (!empty($row['assignment_file'])) { ?>
                            <a class="btn download" href="<?php echo htmlspecialchars($row['assignment_file']); ?>" download title="Download Master File">Prompt</a>
                        <?php } ?>
                        <a class="btn delete"
                           href="?delete_id=<?php echo $row['assignment_id']; ?>"
                           onclick="return confirm('Warning: Deleting this assignment template will wipe out student submission states linked to it. Proceed?')">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='8' style='text-align:center;'>No structural database tracking entities parsed.</td></tr>";
            }
            ?>
            </tbody>
        </table>
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

<script>
// Auto-Dismiss Animation Lifecycle Worker Engine
document.addEventListener("DOMContentLoaded", function() {
    const alertElement = document.getElementById("status-alert");
    if (alertElement) {
        setTimeout(function() {
            alertElement.style.opacity = "0";
            setTimeout(function() {
                alertElement.remove();
            }, 500);
        }, 3500);
    }
});
</script>

</body>
</html>