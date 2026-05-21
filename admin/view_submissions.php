<?php
include '../config/db.php';

$message = "";

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
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $message = "Error deleting assignment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Submissions</title>
    <style>
        * {
            box-sizing: border-box;
        }

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
            margin-bottom: 10px;
        }

        .table-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 60px;
            flex: 1;
        }

        .action-bar {
            width: 80%;
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
            border: 2px solid transparent;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        }

        .btn-track:hover {
            background: white;
            color: #0b1d51;
            border-color: #0b1d51;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        table th {
            background: #0b1d51;
            color: white;
            padding: 15px;
        }

        table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table tr:hover {
            background: #f2f2f2;
        }

        .btn {
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
        }

        .delete {
            background: #dc3545;
        }
        
        .download {
            background: #28a745;
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

    <!-- MATCHED NAVIGATION BAR WITH LOGO PANEL -->
    <nav>
        <div class="logo-section">
            <img src="../assets/images/logo.png" alt="NEXUS UNIVERSITY Logo" class="logo">
            <h2>NEXUS UNIVERSITY</h2>
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

    <!-- MATCHED PAGE HEADER LAYOUT -->
    <section class="title">
        <h1>Student Submissions</h1>
        <?php if(!empty($message)): ?>
            <p style="color: red; font-weight: bold; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </section>

    <!-- DATATABLE CONTAINER -->
    <div class="table-container">
        
        <div class="action-bar">
            <a href="../admin/assignment_submission_report.php" class="btn-track">
                📊 View Submission Tracking Report (Submitted vs Not Submitted)
            </a>
        </div>

        <?php
        /* ================= MAIN QUERY ================= */
        $query = "
        SELECT 
        a.id AS assignment_id,
        a.title,
        a.description,
        a.deadline,
        a.file_path AS assignment_file,
        c.course_name,
        s.file_path AS submission_file,
        s.submitted_at,
        u.id AS student_num,
        u.name AS student_name
        FROM assignments a
        LEFT JOIN courses c ON a.course_id = c.id
        LEFT JOIN submissions s ON a.id = s.assignment_id
        LEFT JOIN users u ON s.user_id = u.id
        ORDER BY a.id DESC
        ";

        $result = mysqli_query($conn, $query);
        ?>

        <table>
            <tr>
                <th>Course</th>
                <th>Assignment</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Submission File</th>
                <th>Submitted At</th>
                <th>Assignment File</th>
                <th>Action</th>
            </tr>

            <?php 
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { 
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['course_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                
                <!-- FIXED CRITICAL VARIABLE ALIAS MAPPINGS HERE -->
                <td><?php echo isset($row['student_num']) ? htmlspecialchars($row['student_num']) : 'Not Submitted'; ?></td>
                <td><?php echo isset($row['student_name']) ? htmlspecialchars($row['student_name']) : 'Not Submitted'; ?></td>
                
                <td>
                    <?php if (!empty($row['submission_file'])) { ?>
                        <a class="btn download" href="<?php echo htmlspecialchars($row['submission_file']); ?>" target="_blank">
                            View
                        </a>
                    <?php } else { echo "No Submission"; } ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['submitted_at'] ?? 'Not Submitted'); ?>
                </td>
                <td>
                    <?php if (!empty($row['assignment_file'])) { ?>
                        <a class="btn download" href="<?php echo htmlspecialchars($row['assignment_file']); ?>" download>
                            Download
                        </a>
                    <?php } else { echo "No File"; } ?>
                </td>
                <td>
                    <a class="btn delete"
                       href="?delete_id=<?php echo $row['assignment_id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this assignment?')">
                        Delete
                    </a>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='8'>No records found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- MATCHED FOOTER COMPLEX -->
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