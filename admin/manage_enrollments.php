<?php
include '../middleware/admin_auth.php';
include '../config/db.php';

/* ADD ENROLLMENT */
if(isset($_POST['add'])){

    $student_id = $_POST['student_id'];
    $course_id  = $_POST['course_id'];

    mysqli_query($conn,
    "INSERT INTO enrollments(student_id, course_id)
    VALUES('$student_id', '$course_id')");
}

/* DELETE ENROLLMENT */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM enrollments WHERE id=$id");
}

/* FETCH STUDENTS */
$students = mysqli_query($conn,
"SELECT * FROM users WHERE role='student'");

/* FETCH COURSES */
$courses = mysqli_query($conn,
"SELECT * FROM courses");

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Manage Enrollments</title>

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

        /* NAVBAR */
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

        /* TITLE */
        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h1 {
            color: #0b1d51;
            font-size: 36px;
        }

        /* CONTAINER BLOCK */
        .container {
            width: 85%;
            margin: auto;
            margin-bottom: 50px;
            flex: 1; 
            background: white;
            padding: 30px;
            border-radius: 6px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        /* TABLES */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
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

        /* INTERACTION FIELDS */
        select, button {
            padding: 10px;
            margin: 5px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #0056b3;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 13px;
        }

        .btn-delete:hover {
            background: #bd2130;
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

<!-- NAVIGATION BAR -->
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

<!-- PAGE TITLE -->
<div class="title">
    <h1>Manage Course Enrollments</h1>
</div>

<!-- MAIN CONTENT BLOCK -->
<div class="container">

    <!-- ADD ENROLLMENT FORM -->
    <form method="POST">

        <!-- STUDENT DROPDOWN -->
        <select name="student_id" required>
            <option value="">Select Student</option>
            <?php while($student = mysqli_fetch_assoc($students)) { ?>
                <option value="<?php echo $student['id']; ?>">
                    <?php echo htmlspecialchars($student['name']); ?>
                </option>
            <?php } ?>
        </select>

        <!-- COURSE DROPDOWN -->
        <select name="course_id" required>
            <option value="">Select Course</option>
            <?php while($course = mysqli_fetch_assoc($courses)) { ?>
                <option value="<?php echo $course['id']; ?>">
                    <?php echo htmlspecialchars($course['course_name']); ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit" name="add">Register Student</button>
    </form>

    <!-- ENROLLMENT DATA TABLE -->
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
            <?php while($row = mysqli_fetch_assoc($enrollments)) { ?>
            <tr>
                <td><code>#<?php echo $row['id']; ?></code></td>
                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                <td>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to remove this enrollment?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

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