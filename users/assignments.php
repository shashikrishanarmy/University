<?php
session_start();
include '../config/db.php';

$message = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// ================= UPLOAD LOGIC =================
if (isset($_POST['submit_assignment'])) {

    $assignment_id = intval($_POST['assignment_id']);

    // CRITICAL FIX: Check if deadline has passed using the Database's clock (NOW())
    $check = mysqli_query($conn, "SELECT deadline, (NOW() > deadline) AS expired FROM assignments WHERE id=$assignment_id");
    $row_deadline = mysqli_fetch_assoc($check);

    if (!$row_deadline || $row_deadline['expired'] == 1) {
        $message = "<div style='text-align:center; margin-bottom:20px;'><p style='color:red; font-weight:bold; margin:0;'>Submission failed: The deadline has passed!</p></div>";
    } else {

        $target_dir = "../uploads/assignments/";

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["assignment_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $target_file_escaped = mysqli_real_escape_string($conn, $target_file);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed = ['pdf','doc','docx','zip'];

        if (in_array($file_type, $allowed)) {

            if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target_file)) {

                // Check existing submission
                $check_sub = mysqli_query($conn,
                    "SELECT id FROM submissions 
                     WHERE assignment_id=$assignment_id AND user_id=$user_id");

                if (mysqli_num_rows($check_sub) > 0) {

                    mysqli_query($conn,
                        "UPDATE submissions 
                         SET file_path='$target_file_escaped', submitted_at=NOW()
                         WHERE assignment_id=$assignment_id AND user_id=$user_id");

                    $message = "<div style='text-align:center; margin-bottom:20px;'><p style='color:green; font-weight:bold; margin:0;'>Updated successfully!</p></div>";

                } else {

                    mysqli_query($conn,
                        "INSERT INTO submissions (assignment_id, user_id, file_path) 
                         VALUES ($assignment_id, $user_id, '$target_file_escaped')");

                    $message = "<div style='text-align:center; margin-bottom:20px;'><p style='color:green; font-weight:bold; margin:0;'>Submitted successfully!</p></div>";
                }

            } else {
                $message = "<div style='text-align:center; margin-bottom:20px;'><p style='color:red; font-weight:bold; margin:0;'>Upload failed!</p></div>";
            }

        } else {
            $message = "<div style='text-align:center; margin-bottom:20px;'><p style='color:red; font-weight:bold; margin:0;'>Invalid file type!</p></div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NEXUS UNIVERSITY - Assignments</title>

    <style>
        *{
            box-sizing:border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #7480b5ff;
            padding-top: 100px;
            
            /* --- FLEXBOX WRAPPER KEEPS FOOTER ON BOTTOM --- */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* NAVBAR */
        nav{
            background:#0b1d51;
            padding:15px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            position:fixed;
            top:0;
            left:0;
            right:0;
            z-index:1000;
        }

        nav a{
            color:white;
            text-decoration:none;
            margin:10px;
        }

        nav a:hover{
            color:gold;
        }

        .logo-section{
            display:flex;
            align-items:center;
            gap:10px;
        }

        .logo{
            width:50px;
            height:50px;
            border-radius:50%;
        }

        /* TITLE */
        .title{
            text-align:center;
            margin-bottom:20px;
        }

        .title h1{
            color:#0b1d51;
            font-size:36px;
        }

        /* MAIN CONTENT AREA CONTAINER */
        .container {
            width: 85%;
            margin: auto;
            margin-bottom: 50px;
            /* Expands to force footer downward if cards are thin */
            flex: 1; 
        }

        .card {
            background:white;
            padding:20px;
            margin-bottom:20px;
            border-radius:8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        .btn {
            padding:8px 12px;
            text-decoration:none;
            border-radius:5px;
            color:white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .download { background:#007bff; display: inline-block; }
        .download:hover { background:#0056b3; }
        
        .submit { background:#28a745; }
        .submit:hover { background:#218838; }

        .status {
            font-weight:bold;
            margin-top:10px;
            margin-bottom:10px;
        }

        /* FOOTER STYLING */
        footer {
            background: #0b1d51;
            color: white;
            padding: 20px;
            margin-top: auto; /* Attaches container dynamically to page baseline */
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

<!-- MATCHED NAVIGATION BAR WITH LOGO PANEL -->
<nav>
    <div class="logo-section">
        <img src="../assets/images/logo.png" class="logo">
        <h2 style="color:white; margin:0;">
            NEXUS UNIVERSITY
        </h2>
    </div>
    <div>
        <a href="../users/home.php" style="color:gold;">
            HOME
        </a>
        <a href="../users/view_materials.php">
            MATERIALS
        </a>
        <a href="../users/assignments.php">
            ASSIGNMENTS
        </a>
        <a href="../auth/logout.php">
            LOGOUT
        </a>
    </div>
</nav>

<!-- MATCHED PAGE HEADER LAYOUT -->
<div class="title">
    <h1>My Assignments</h1>
</div>

<!-- CONTAINER BLOCK WITH INLINE MESSAGE HANDLING -->
<div class="container">

    <div><?php echo $message; ?></div>

    <?php
    // CRITICAL FIX: We calculate "expired" right in the MySQL Query string using (NOW() > a.deadline)
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

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)){
            $deadline = $row['deadline'];
            $expired = ($row['expired'] == 1); // Checked via Database Time
            $submitted = !empty($row['my_file']);
        ?>

        <div class="card">
            <h3 style="color:#0b1d51; margin-top:0;"><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p><b>Deadline:</b> <?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($deadline))); ?></p>

            <!-- DOWNLOAD ASSIGNMENT FILE (ADMIN UPLOAD) -->
            <?php if (!empty($row['file_path'])) { ?>
                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" class="btn download" download>
                    Download Assignment File
                </a>
            <?php } ?>

            <!-- STATUS VISUALIZATION -->
            <div class="status">
                <?php
                if ($submitted) {
                    echo "<span style='color:#28a745;'>✅ Submitted on " . htmlspecialchars(date('M d, Y h:i A', strtotime($row['submitted_at']))) . "</span>";
                } elseif ($expired) {
                    echo "<span style='color:#dc3545;'>❌ Deadline Passed</span>";
                } else {
                    echo "<span style='color:#ffc107;'>⏳ Not Submitted</span>";
                }
                ?>
            </div>

            <!-- SUBMISSION FIELDS INTERACTIVE SECTION -->
            <?php if (!$expired) { ?>
                <form method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
                    <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
                    <input type="file" name="assignment_file" required>
                    <br><br>
                    <button type="submit" name="submit_assignment" class="btn submit">
                        <?php echo $submitted ? "Re-submit Assignment" : "Submit Assignment"; ?>
                    </button>
                </form>
            <?php } else { ?>
                <p style="color: red; font-style: italic; font-weight: bold; margin-bottom: 0;">Submissions are closed for this assignment.</p>
            <?php } ?>
        </div>

        <?php 
        } 
    } else {
        echo "<div class='card' style='text-align:center;'><p style='margin:0; font-size:16px;'>No assignments issued for your registered courses yet.</p></div>";
    }
    ?>

</div>

<!-- INTEGRATED UNIVERSITY FOOTER FIXED TO SCREEN FOOT -->
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