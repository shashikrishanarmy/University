<?php
include '../config/db.php';
session_start();

// Redirect to login page if user session is not active
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);



// Get total materials using course_materials table 
$materials_count = 0;
$mat_query = "
    SELECT COUNT(cm.id) AS total_materials 
    FROM course_materials cm
    JOIN enrollments e ON cm.course_id = e.course_id
    WHERE e.student_id = $user_id
";
$mat_result = mysqli_query($conn, $mat_query);
if ($mat_result && $mat_row = mysqli_fetch_assoc($mat_result)) {
    $materials_count = intval($mat_row['total_materials']);
}


$assignments_issued = 0;
$assignments_submitted = 0;
$assignments_pending = 0;

// Get the total assignments according to the courses the students enrolled
$asm_issued_query = "
    SELECT COUNT(a.id) AS total_issued 
    FROM assignments a
    JOIN enrollments e ON a.course_id = e.course_id
    WHERE e.student_id = $user_id
";
$asm_issued_result = mysqli_query($conn, $asm_issued_query);
if ($asm_issued_result && $issued_row = mysqli_fetch_assoc($asm_issued_result)) {
    $assignments_issued = intval($issued_row['total_issued']);
}

// Get total assignments already submitted by logged-in student
$asm_sub_query = "
    SELECT COUNT(s.id) AS total_submitted 
    FROM submissions s
    JOIN assignments a ON s.assignment_id = a.id
    JOIN enrollments e ON a.course_id = e.course_id
    WHERE e.student_id = $user_id AND s.user_id = $user_id
";
$asm_sub_result = mysqli_query($conn, $asm_sub_query);
if ($asm_sub_result && $sub_row = mysqli_fetch_assoc($asm_sub_result)) {
    $assignments_submitted = intval($sub_row['total_submitted']);
}

// pending assignments check
$assignments_pending = $assignments_issued - $assignments_submitted;

// protection rule against unexpected null values
if ($assignments_pending < 0) {
    $assignments_pending = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NEXUS UNIVERSITY - Student Home</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-image: url('../assets/images/stdash.jpg');
            background-size: cover;      
            background-position: center; 
            background-repeat: no-repeat;
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
            width: 100%;
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

        
        .hero {
            text-align: center;
            padding: 50px 20px;
        }

        .hero h1 {
            color: #0b1d51;
            font-size: 42px;
        }

        .hero p {
            font-size: 18px;
            color: #333;
        }

        
        .cards {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            padding: 30px;
        }

        .card {
            background: white;
            width: 320px; 
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card h3 {
            color: #0b1d51;
            margin-top: 0;
            margin-bottom: 5px;
        }

        .card p {
            color: #555;
            font-size: 14px;
            margin-bottom: 15px;
        }

        
        .counter-badge {
            display: inline-block;
            background-color: #dc3545; /* Notification Red */
            color: white;
            font-weight: bold;
            font-size: 14px;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }

        
        .badge-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 15px;
            text-align: left;
        }

        .badge-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            background: #f8f9fa;
            border-left: 4px solid #7480b5ff;
        }

        .badge-count {
            padding: 2px 8px;
            border-radius: 10px;
            color: white;
            font-size: 12px;
        }

        
        .card a {
            display: inline-block;
            margin-top: auto;
            padding: 10px 15px;
            background: #0b1d51;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            width: 100%;
            font-weight: bold;
        }

        .card a:hover {
            background: #142c7a;
            color: gold;
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

        .footer-left p { margin: 5px 0; }
        .map-container iframe { width: 300px; height: 200px; }

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
        <img src="../assets/images/logo.png" alt="Logo" class="logo">
        <h2 style="color:white; margin:0;">NEXUS UNIVERSITY</h2>
    </div>

    <div>
        <a href="../users/home.php" style="color:gold;">STUDENT PANEL</a>
        <a href="../users/view_materials.php">MATERIALS</a>
        <a href="../users/assignments.php">ASSIGNMENTS</a>
        <a href="../users/view_timetable.php">TIMETABLE</a>
        <a href="../auth/logout.php">LOGOUT</a>
    </div>
</nav>

<section class="hero">
    <h1>Welcome to Nexus University</h1>
    <p style="font-size: 30px;">Explore courses, access study materials, and enhance your learning experience.</p>
</section>

<section class="cards">

    <div class="card">
        <div>
            <h3>📄 Materials</h3>
            <p>Count of the Available Lecture Materials</p>
        </div>
        <div style="margin: 20px 0;">
            <div class="counter-badge">
                📚 <?php echo $materials_count; ?> Available
            </div>
        </div>
        
    </div>

    <div class="card">
        <div>
            <h3>🎓 Assignments</h3>
            <p>Assignments Summary</p>
        </div>
        
        <div class="badge-list">
            <div class="badge-row" style="border-left-color: #0b1d51;">
                <span>📋 Total Issued Tasks</span>
                <span class="badge-count" style="background: #0b1d51;"><?php echo $assignments_issued; ?></span>
            </div>
            
            <div class="badge-row" style="border-left-color: #28a745;">
                <span>✅ Submitted Solutions</span>
                <span class="badge-count" style="background: #28a745;"><?php echo $assignments_submitted; ?></span>
            </div>
            
            <div class="badge-row" style="border-left-color: #dc3545;">
                <span>⏳ Still To Be Submitted</span>
                <span class="badge-count" style="background: #dc3545;"><?php echo $assignments_pending; ?></span>
            </div>
        </div>

        
    </div>

</section>

<footer>
    <div class="footer-container">
        <div class="footer-left">
            <h3>Contact Us</h3>
            <p>📞 Call: 0114325690</p>
            <p>✉️ Email: nexusuniversity@gmail.com</p>
            <p>📍 Address: Nexus University, New Kandy Road, Malabe</p>
        </div>

        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps?q=Malabe,Sri%20Lanka&output=embed"
                style="border:0;" 
                loading="lazy">
            </iframe>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>
    </div>
</footer>

</body>
</html>