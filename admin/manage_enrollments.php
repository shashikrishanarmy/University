<?php
// Start session at the very top to preserve feedback message states across header redirections
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../middleware/admin_auth.php';
include '../config/db.php';

/* ADD ENROLLMENT */
if(isset($_POST['add'])){
    // Using intval to securely sanitize input flags and prevent injection anomalies
    $student_id = intval($_POST['student_id']);
    $course_id  = intval($_POST['course_id']);

    if ($student_id > 0 && $course_id > 0) {
        $insert_query = "INSERT INTO enrollments(student_id, course_id) VALUES('$student_id', '$course_id')";
        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-success'>Student registered and enrolled successfully!</div>";
        } else {
            $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Database Error: Failed to complete enrollment.</div>";
        }
    } else {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Error: Please select both a valid student and a course.</div>";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* DELETE ENROLLMENT */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM enrollments WHERE id=$id";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-success'>Enrollment record removed successfully.</div>";
    } else {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Database Error: Could not delete enrollment record.</div>";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* FETCH STUDENTS */
$students = mysqli_query($conn, "SELECT * FROM users WHERE role='student'");

/* FETCH ALL COURSES (Kept as baseline fallback) */
$courses = mysqli_query($conn, "SELECT * FROM courses");

/* FETCH ENROLLMENTS */
$enrollments = mysqli_query($conn,
"SELECT enrollments.id,
users.name,
courses.course_name
FROM enrollments
JOIN users
ON enrollments.student_id = users.id
JOIN courses
ON enrollments.course_id = courses.id
");

// Consume current session message to clear it for the next execution run
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
    <title>NEXUS UNIVERSITY - Manage Enrollments</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; font-family: Arial, sans-serif; background-color: #7480b5ff; padding-top: 100px;
            display: flex; flex-direction: column; min-height: 100vh;
        }
        nav {
            background: #0b1d51; padding: 15px; display: flex; justify-content: space-between; align-items: center;
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        }
        nav a { color: white; text-decoration: none; margin: 10px; }
        nav a:hover { color: gold; }
        .logo-section { display: flex; align-items: center; gap: 10px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }
        .title { text-align: center; margin-bottom: 20px; }
        .title h1 { color: #0b1d51; font-size: 36px; margin-top: 20px; }
        
        .container {
            width: 85%; margin: auto; margin-bottom: 50px; flex: 1; 
            background: white; padding: 30px; border-radius: 6px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        /* Message Box Notification Layout Wrapper */
        .msg-box { 
            text-align: center; 
            margin-bottom: 20px; 
        }

        /* MODERN SMOOTH FADE ALERT BOX STYLING */
        .alert-box {
            padding: 12px;
            width: 100%;
            margin: 0 auto 15px auto;
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

        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        table th { background: #343a40; color: white; padding: 12px; text-align: left; font-size: 14px; }
        table td { padding: 12px; border: 1px solid #dee2e6; font-size: 14px; }
        table tr:hover { background: #f1f3f5; }
        select, button { padding: 10px; margin: 5px; font-size: 14px; border-radius: 4px; border: 1px solid #ccc; }
        button { background: #007bff; color: white; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background: #0056b3; }
        .btn-delete { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 13px; }
        .btn-delete:hover { background: #bd2130; }
        
        footer { background: #0b1d51; color: white; padding: 20px; margin-top: 40px; }
        .footer-container { display: flex; justify-content: space-between; align-items: center; }
        .footer-left p { margin: 5px 0; }
        .map-container iframe { width: 300px; height: 200px; }
        .footer-bottom { text-align: center; margin-top: 15px; border-top: 1px solid #ffffff33; padding-top: 10px; }
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

<div class="title">
    <h1>Manage Course Enrollments</h1>
</div>

<div class="container">

    <div class="msg-box"><?php echo $message; ?></div>

    <form method="POST">
        <select name="student_id" id="student_select" required>
            <option value="">Select Student</option>
            <?php while($student = mysqli_fetch_assoc($students)) { ?>
                <option value="<?php echo $student['id']; ?>">
                    <?php echo htmlspecialchars($student['name']); ?>
                </option>
            <?php } ?>
        </select>

        <select name="course_id" id="course_select" required>
            <option value="">Select Course</option>
            <?php while($course = mysqli_fetch_assoc($courses)) { ?>
                <option value="<?php echo $course['id']; ?>">
                    <?php echo htmlspecialchars($course['course_name']); ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit" name="add">Register Student</button>
    </form>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">ID</th>
                <th style="width: 45%;">Student Name</th>
                <th style="width: 30%;">Enrolled Course</th>
                <th style="width: 15%;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($enrollments) > 0) { 
                while($row = mysqli_fetch_assoc($enrollments)) { ?>
                <tr>
                    <td><code>#<?php echo $row['id']; ?></code></td>
                    <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to remove this enrollment?');">Delete</a>
                    </td>
                </tr>
                <?php } 
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No student enrollment records found inside database.</td></tr>";
            } ?>
        </tbody>
    </table>
</div>

<footer>
    <div class="footer-container">
        <div class="footer-left">
            <h3 style="margin-top: 0; color: gold;">Contact Us</h3>
            <p>📞 Call: 0114325690</p>
            <p>✉️ Email: nexusuniversity@gmail.com</p>
            <p>📍 Address: Nexus University, New Kandy Road, Malabe</p>
        </div>
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=..." loading="lazy"></iframe>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>
    </div>
</footer>

<script>
// Dynamic Select Controller
document.getElementById('student_select').addEventListener('change', function() {
    const studentId = this.value;
    const courseDropdown = document.getElementById('course_select');

    if (!studentId) {
        courseDropdown.innerHTML = '<option value="">Select Course</option>';
        return;
    }

    fetch('get_student_course.php?student_id=' + studentId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                courseDropdown.innerHTML = `<option value="${data.id}">${data.course_name}</option>`;
            } else {
                courseDropdown.innerHTML = '<option value="">❌ No requested course found</option>';
            }
        })
        .catch(error => {
            console.error('Error fetching course data:', error);
            courseDropdown.innerHTML = '<option value="">Error fetching data</option>';
        });
});

// Auto-Dismiss Interactive Alert Engine
document.addEventListener("DOMContentLoaded", function() {
    const alertElement = document.getElementById("status-alert");
    if (alertElement) {
        // Keep notification visible for 3.5 seconds
        setTimeout(function() {
            alertElement.style.opacity = "0";
            
            // Drop element cleanly from active viewport layout when fade completes
            setTimeout(function() {
                alertElement.remove();
            }, 500);
        }, 3500);
    }
});
</script>

</body>
</html>