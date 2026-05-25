<?php
include '../config/db.php';

$message = "";
$msg_style = "";

if (isset($_POST['submit_request'])) {
    // Collect & sanitize fields to match your exact database columns
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $requested_course = mysqli_real_escape_string($conn, $_POST['requested_course']);
    $nic_or_passport = mysqli_real_escape_string($conn, $_POST['nic_or_passport']);
    $highest_qualification = mysqli_real_escape_string($conn, $_POST['highest_qualification']);

    // INSERTION QUERY: Matches your table name 'request_courses' and schema layout
    $query = "INSERT INTO request_courses (student_name, contact_number, email, requested_course, nic_or_passport, highest_qualification, status) 
              VALUES ('$student_name', '$contact_number', '$email', '$requested_course', '$nic_or_passport', '$highest_qualification', 'Pending')";

    if (mysqli_query($conn, $query)) {
        $message = "🎉 Course request submitted successfully! The academic registry office will review your details soon.";
        $msg_style = "color: #0b1d51; background: #eef0f8; border: 1px solid #0b1d51;";
    } else {
        $message = "❌ Error submitting request: " . mysqli_error($conn);
        $msg_style = "color: red; background: #ffdddd; border: 1px solid red;";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Request a Course</title>
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

        .logo-section h2 {
            color: white;
            margin: 0;
        }

        .title {
            text-align: center;
            padding: 30px 20px;
        }

        .title h1 {
            color: #0b1d51;
            font-size: 36px;
            margin: 0;
        }

        .container {
            width: 50%;
            margin: auto;
            margin-bottom: 60px;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.15);
        }

        form h3 {
            color: #0b1d51;
            margin-top: 0;
            border-bottom: 2px solid #0b1d51;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 6px 0 16px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        button {
            padding: 12px 24px;
            background: #0b1d51;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 4px;
            font-size: 15px;
        }

        button:hover {
            background: #112b73;
            color: gold;
        }

        .btn-back {
            background: #6c757d;
            text-decoration: none;
            color: white;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 15px;
            text-align: center;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .msg-box {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }

        footer {
            background: #0b1d51;
            color: white;
            padding: 20px;
            margin-top: 60px;
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
            <h2 style="color:white;">NEXUS UNIVERSITY</h2>
        </div>
        <div>
            <a href="../index.php">HOME</a>
            <a href="../users/courses.php">COURSES</a>
            <a href="../auth/login.php">LOGIN</a>
        </div>
    </nav>

    <section class="title">
        <h1>Course Registration Request</h1>
    </section>

    <div class="container">
        
        <?php if(!empty($message)): ?>
            <div class="msg-box" style="<?php echo $msg_style; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <h3>Personal & Enrollment Details</h3>

            <label>Full Name</label>
            <input type="text" name="student_name" placeholder="Enter your full name" required>

            <label>Contact Number</label>
            <input type="text" name="contact_number" placeholder="e.g., 077XXXXXXX" required>

            <label>Email Address</label>
            <input type="email" name="email" placeholder="example@domain.com" required>

            <label>Select Desired Course</label>
            <select name="requested_course" required>
                <option value="">-- Choose from available programs --</option>
                <?php
                // Dynamically populate options directly using the human-readable names from the courses table
                $course_query = mysqli_query($conn, "SELECT course_name FROM courses");
                if ($course_query && mysqli_num_rows($course_query) > 0) {
                    while($c = mysqli_fetch_assoc($course_query)){
                        echo "<option value='{$c['course_name']}'>" . htmlspecialchars($c['course_name']) . "</option>";
                    }
                }
                ?>
            </select>

            <label>NIC or Passport Number</label>
            <input type="text" name="nic_or_passport" placeholder="Enter identity document number" required>

            <label>Highest Academic Qualification</label>
            <select name="highest_qualification" required>
                <option value="">-- Select Qualification --</option>
                <option value="G.C.E. O/L">G.C.E. O/L</option>
                <option value="G.C.E. A/L">G.C.E. A/L</option>
                <option value="Diploma / Advanced Diploma">Diploma / Advanced Diploma</option>
                <option value="Bachelor's Degree">Bachelor's Degree</option>
            </select>

            <div class="btn-group">
                <button type="submit" name="submit_request">Submit Request</button>
                <a href="courses.php" class="btn-back">Cancel</a>
            </div>
        </form>
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