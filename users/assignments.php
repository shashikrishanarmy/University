<?php
// Start session for header redirections
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);


// UPLOAD Logic for Assignment Submissions
if (isset($_POST['submit_assignment'])) {
    $assignment_id = intval($_POST['assignment_id']);

    // Check if deadline has passed using the Database's clock (NOW())
    $check = mysqli_query($conn, "SELECT deadline, (NOW() > deadline) AS expired FROM assignments WHERE id=$assignment_id");
    $row_deadline = mysqli_fetch_assoc($check);

    if (!$row_deadline || $row_deadline['expired'] == 1) {
        $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Submission failed: The deadline has passed!</div>";
    } else {
        $target_dir = "../uploads/assignments/";

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); //0777 use in php to give read, write and execute permissions to the folder for all users.
        }

        $file_name = time() . "_" . basename($_FILES["assignment_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $target_file_escaped = mysqli_real_escape_string($conn, $target_file);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed = ['pdf','doc','docx','zip'];

        if (in_array($file_type, $allowed)) {
            if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target_file)) {
                // Check existing submission
                $check_sub = mysqli_query($conn, "SELECT id FROM submissions WHERE assignment_id=$assignment_id AND user_id=$user_id");

                if (mysqli_num_rows($check_sub) > 0) {
                    mysqli_query($conn, "UPDATE submissions SET file_path='$target_file_escaped', submitted_at=NOW() WHERE assignment_id=$assignment_id AND user_id=$user_id");
                    $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-success'>Assignment updated successfully!</div>";
                } else {
                    mysqli_query($conn, "INSERT INTO submissions (assignment_id, user_id, file_path) VALUES ($assignment_id, $user_id, '$target_file_escaped')");
                    $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-success'>Assignment submitted successfully!</div>";
                }
            } else {
                $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Upload failed! could not save the file.</div>";
            }
        } else {
            $_SESSION['alert_msg'] = "<div id='status-alert' class='alert-box alert-danger'>Invalid file type! Allowed only PDF, DOC, DOCX, ZIP.</div>";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// current session message to clear it for the next execution run
$message = "";
if (isset($_SESSION['alert_msg'])) {
    $message = $_SESSION['alert_msg'];
    unset($_SESSION['alert_msg']);
}

$user_id = intval($_SESSION['user_id']);

// Fetch the logged-in student's full name from the 'users' table

$name = "Student"; // Default fallback value
$user_query = "SELECT name FROM users WHERE id = $user_id LIMIT 1"; 

$user_result = mysqli_query($conn, $user_query);
if ($user_result && $user_row = mysqli_fetch_assoc($user_result)) {
    $name = $user_row['name']; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Assignments</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #7480b5ff;
            padding-top: 90px;
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
nav a { 
    color: white; 
    text-decoration: none; 
    margin: 10px; 
    font-weight: bold;
}
nav a:hover { 
    color: gold; 
}

/* New CSS Rule: Applies yellow highlight to the active page link */
nav a.active {
    color: gold !important;
}

        .logo-section { display: flex; align-items: center; gap: 10px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }

         .user-welcome {
            color: #2ecc71;
            font-weight: bold;
            margin-right: 10px;
            margin-left: 10px;
            font-size: 14px;
        }
        
        .title { text-align: center; margin-bottom: 10px; }
        .title h1 { color: #0b1d51; font-size: 32px; margin: 10px 0; }

        
        .container {
            width: 95%;
            max-width: 1400px;
            margin: 0 auto 30px auto;
            flex: 1; 
        }

        .main-layout {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        /* LEFT SIDE COLUMN: ASSIGNMENT GRID (70%) */
        .assignment-section {
            width: 70%;
        }

        .assignment-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
        }

        .card {
            background: white;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
            width: calc(50% - 8px); /* Balanced 2 Columns inside assignment section */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 280px;
        }

        .card h3 {
            color: #0b1d51;
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .card p {
            font-size: 13px;
            color: #444;
            margin: 4px 0;
            line-height: 1.4;
        }

        /* RIGHT SIDE COLUMN*/
        .grades-sidebar {
            width: 30%;
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
            min-width: 300px;
        }

        .grades-sidebar h2 {
            color: #0b1d51;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 2px solid #0b1d51;
            padding-bottom: 5px;
        }

        .grade-item {
            background: #f8f9fa;
            border-left: 4px solid #0b1d51; 
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 0 4px 4px 0;
        }

        .grade-item p {
            margin: 3px 0;
            font-size: 13px;
            color: #333;
        }

        .grade-badge {
            display: inline-block;
            background: #28a745; 
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }

        
        .msg-box { text-align: center; margin-bottom: 15px; }
        .alert-box {
            padding: 10px; width: 100%; border-radius: 5px; font-weight: bold; text-align: center; opacity: 1;
            transition: opacity 0.5s ease-out; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        
        .btn { padding: 6px 10px; text-decoration: none; border-radius: 4px; color: white; border: none; cursor: pointer; font-weight: bold; font-size: 12px; text-align: center; }
        .download { background: #007bff; display: inline-block; margin-top: 6px; }
        .download:hover { background: #0056b3; }
        .submit { background: #28a745; width: 100%; }
        .submit:hover { background: #218838; }

        .status { font-weight: bold; font-size: 12px; margin: 8px 0; padding: 4px 0; border-top: 1px dashed #eee; }
        .upload-form { margin-top: auto; background: #f8f9fa; padding: 8px; border-radius: 4px; border: 1px solid #e9ecef; }
        .upload-form input[type="file"] { font-size: 11px; width: 100%; }

        
        footer { background: #0b1d51; color: white; padding: 15px; margin-top: auto; width: 100%; }
        .footer-container { display: flex; justify-content: space-between; align-items: center; max-width: 90%; margin: auto; flex-wrap: wrap; gap: 15px; }
        .footer-left h3 { margin: 0 0 5px 0; font-size: 16px; }
        .footer-left p { margin: 3px 0; font-size: 12px; }
        .map-container iframe { width: 260px; height: 130px; border: 0; }
        .footer-bottom { text-align: center; margin-top: 10px; border-top: 1px solid #ffffff33; padding-top: 8px; font-size: 12px; }

        /* RESPONSIVE Web Design */
        @media (max-width: 992px) {
            .main-layout { flex-direction: column-reverse; } 
            .grades-sidebar, .assignment-section { width: 100%; }
            .card { width: calc(50% - 8px); }
        }
        @media (max-width: 650px) {
            .card { width: 100%; }
        }
    </style>
</head>
<body>

<?php
// Get the current running file filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav>
    <div class="logo-section">
        <img src="../assets/images/logo.png" class="logo" alt="Nexus Logo">
        <h2 style="color:white; margin:0; font-size: 20px;">NEXUS UNIVERSITY</h2>
    </div>
    <div>
        <a href="../users/home.php" class="<?php echo ($current_page == 'home.php') ? 'active' : ''; ?>">STUDENT PANEL</a>
        <a href="../users/view_materials.php" class="<?php echo ($current_page == 'view_materials.php') ? 'active' : ''; ?>">MATERIALS</a>
        <a href="../users/assignments.php" class="<?php echo ($current_page == 'assignments.php') ? 'active' : ''; ?>">ASSIGNMENTS</a>
        <a href="../users/view_timetable.php" class="<?php echo ($current_page == 'view_timetable.php') ? 'active' : ''; ?>">TIMETABLE</a>
        <span class="user-welcome">👤 <?php echo htmlspecialchars($name); ?></span>
        <a href="../auth/logout.php" style="color: #ff4d4d;">LOGOUT</a>
    </div>
</nav>

<div class="title">
    <h1>My Assignments & Academic Grades</h1>
</div>

<div class="container">

    <div class="msg-box"><?php echo $message; ?></div>

    <div class="main-layout">
        
        <div class="assignment-section">
            <div class="assignment-grid">
                <?php
                $query = "
                SELECT 
                a.*,
                (NOW() > a.deadline) AS expired,
                s.file_path AS my_file,
                s.submitted_at
                FROM assignments a
                JOIN enrollments e ON a.course_id = e.course_id
                LEFT JOIN submissions s ON a.id = s.assignment_id AND s.user_id = $user_id
                WHERE e.student_id = $user_id
                ORDER BY a.deadline ASC
                ";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $deadline = $row['deadline'];
                        $expired = ($row['expired'] == 1);
                        $submitted = !empty($row['my_file']);
                    ?>

                    <div class="card">
                        <div>
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p><b>Deadline:</b> <span style="color:#6f42c1;"><?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($deadline))); ?></span></p>

                            <?php if (!empty($row['file_path'])) { ?>
                                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" class="btn download" download>
                                    📥 Download Prompt File
                                </a>
                            <?php } ?>
                        </div>

                        <div>
                            <div class="status">
                                Status: 
                                <?php
                                if ($submitted) {
                                    echo "<span style='color:#28a745;'>✅ Submitted (" . htmlspecialchars(date('M d, Y', strtotime($row['submitted_at']))) . ")</span>";
                                } elseif ($expired) {
                                    echo "<span style='color:#dc3545;'>❌ Closed</span>";
                                } else {
                                    echo "<span style='color:#ffc107;'>⏳ Pending Submission</span>";
                                }
                                ?>
                            </div>

                            <?php if (!$expired) { ?>
                                <form method="POST" enctype="multipart/form-data" class="upload-form">
                                    <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
                                    <input type="file" name="assignment_file" required>
                                    <div style="margin-top: 6px;">
                                        <button type="submit" name="submit_assignment" class="btn submit">
                                            <?php echo $submitted ? "Update Submission" : "Submit Work"; ?>
                                        </button>
                                    </div>
                                </form>
                            <?php } else { ?>
                                <p style="color: #dc3545; font-style: italic; font-size: 12px; font-weight: bold; margin: 0; text-align: center;">Lockout: Submissions are closed.</p>
                            <?php } ?>
                        </div>
                    </div>

                    <?php 
                    } 
                } else {
                    echo "<div class='alert-box' style='background: white; color: #333; width:100%;'>No assignments issued for your registered courses yet.</div>";
                }
                ?>
            </div>
        </div>

        <div class="grades-sidebar">
            <h2>🎯 My Graded Exercises</h2>
            <?php
            // Fetch details containing Student ID (u.id), Name (u.name), Exercise Title (a.title), and Grade (s.grade)
            $grades_query = "
                SELECT 
                    u.id AS student_id,
                    u.name AS student_name,
                    a.title AS exercise_title,
                    s.grade
                FROM submissions s
                JOIN assignments a ON s.assignment_id = a.id
                JOIN users u ON s.user_id = u.id
                WHERE s.user_id = $user_id 
                  AND s.grade IS NOT NULL 
                  AND s.grade != ''
                ORDER BY s.submitted_at DESC
            ";
            
            $grades_result = mysqli_query($conn, $grades_query);

            //check if there are any graded submissions and display them in the sidebar
            if (mysqli_num_rows($grades_result) > 0) {
                while ($grade_row = mysqli_fetch_assoc($grades_result)) {
                    ?>
                    <div class="grade-item">
                        <p><b>ID:</b> <?php echo htmlspecialchars($grade_row['student_id']); ?></p>
                        <p><b>Student:</b> <?php echo htmlspecialchars($grade_row['student_name']); ?></p>
                        <p><b>Exercise:</b> <?php echo htmlspecialchars($grade_row['exercise_title']); ?></p>
                        <p style="margin-top: 5px;"><b>Grade:</b> <span class="grade-badge"><?php echo htmlspecialchars($grade_row['grade']); ?></span></p>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='color: #777; font-style: italic; font-size: 13px; text-align: center; padding: 10px 0;'>No assignments have been graded by an administrator yet.</p>";
            }
            ?>
        </div>

    </div> 
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
// Auto-Dismiss Interactive UI Timer Engine
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