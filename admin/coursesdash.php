<?php
// Start session at the very top to preserve feedback message states across header redirections
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/db.php';

// ADD COURSE
if (isset($_POST['add_course'])) {
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $fee = mysqli_real_escape_string($conn, $_POST['fee']);

    if (!empty($course_name) && !empty($duration) && !empty($fee)) {
        $insert_query = "INSERT INTO courses (course_name, duration, fee) VALUES ('$course_name', '$duration', '$fee')";
        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-success'>Course added successfully!</div>";
        } else {
            $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Database Error: Failed to add course.</div>";
        }
    } else {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Error: All form fields are required.</div>";
    }
    header("Location: " . $_SERVER['PHP_SELF']); 
    exit();
}

// UPDATE COURSE
if (isset($_POST['update_course'])) {
    $id = intval($_POST['course_id']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $fee = mysqli_real_escape_string($conn, $_POST['fee']);

    $update_query = "UPDATE courses SET course_name='$course_name', duration='$duration', fee='$fee' WHERE id=$id";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-success'>Course details updated successfully!</div>";
    } else {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Database Error: Failed to modify course.</div>";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// DELETE COURSE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM courses WHERE id=$id";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-success'>Course removed successfully!</div>";
    } else {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Database Error: Failed to delete course.</div>";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetching standard edit info if an item edit is triggered
$edit_mode = false;
$edit_row = ['id' => '', 'course_name' => '', 'duration' => '', 'fee' => ''];
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM courses WHERE id=$id";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($row = mysqli_fetch_assoc($edit_result)) {
        $edit_mode = true;
        $edit_row = $row;
    }
}

// Consume current session message to clear it for the next run
$message = "";
if (isset($_SESSION['alert_msg'])) {
    $message = $_SESSION['alert_msg'];
    unset($_SESSION['alert_msg']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Dashboard</title>
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
            margin: 0;
        }

        /* Message Box Notification Layout */
        .msg-box { 
            text-align: center; 
            margin-bottom: 25px; 
        }

        /* MODERN SMOOTH FADE ALERT BOX STYLING */
        .alert-box {
            padding: 12px;
            width: 80%;
            margin: 0 auto;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            opacity: 1;
            transition: opacity 0.5s ease-out;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Form Container Styles */
        .form-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .crud-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            width: 80%;
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 150px;
        }

        .form-group label {
            margin-bottom: 5px;
            color: #0b1d51;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-add { background: #28a745; color: white; }
        .btn-update { background: #ffc107; color: #333; }
        .btn-edit { background: #007bff; color: white; padding: 5px 10px; margin-right: 5px;}
        .btn-delete { background: #dc3545; color: white; padding: 5px 10px; }
        .btn-cancel { background: #6c757d; color: white; }

        .table-container {
            display: flex;
            justify-content: center;
            margin-bottom: 60px;
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

        footer {
            background: #0b1d51;
            color: white;
            padding: 20px;
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
        <h1>Courses Dashboard</h1>
    </section>

    <div class="msg-box"><?php echo $message; ?></div>

    <div class="form-container">
        <form action="" method="POST" class="crud-form">
            <input type="hidden" name="course_id" value="<?php echo $edit_row['id']; ?>">
            
            <div class="form-group">
                <label>Course Name</label>
                <input type="text" name="course_name" value="<?php echo htmlspecialchars($edit_row['course_name']); ?>" placeholder="e.g., Computer Science" required>
            </div>
            
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" value="<?php echo htmlspecialchars($edit_row['duration']); ?>" placeholder="e.g., 3 Years" required>
            </div>
            
            <div class="form-group">
                <label>Fee (LKR)</label>
                <input type="number" name="fee" value="<?php echo htmlspecialchars($edit_row['fee']); ?>" placeholder="e.g., 150000" required>
            </div>

            <div>
                <?php if ($edit_mode): ?>
                    <button type="submit" name="update_course" class="btn btn-update">Update Course</button>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-cancel">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="add_course" class="btn btn-add">Add Course</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Duration</th>
                <th>Fee</th>
                <th>Actions</th>
            </tr>

            <?php
            $query = "SELECT * FROM courses";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)){
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['duration']); ?></td>
                        <td><?php echo number_format($row['fee']); ?></td>
                        <td>
                            <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='5'>No courses found.</td></tr>";
            }
            ?>
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
        document.addEventListener("DOMContentLoaded", function() {
            const alertElement = document.getElementById("status-alert");
            if (alertElement) {
                // Keep notification visible for 3.5 seconds
                setTimeout(function() {
                    alertElement.style.opacity = "0";
                    
                    // Drop element from DOM hierarchy when fade completes
                    setTimeout(function() {
                        alertElement.remove();
                    }, 500);
                }, 3500);
            }
        });
    </script>

</body>
</html>