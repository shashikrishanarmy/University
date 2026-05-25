<?php
include '../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY</title>

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
            padding: 40px 20px 20px 20px;
        }

        .title h1 {
            color: #0b1d51;
            font-size: 40px;
            margin: 0;
        }

        /* NEW BUTTON ACTION CONTAINER */
        .action-container {
            width: 80%;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: flex-start;
        }

        /* NEW POLISHED BUTTON VIEW DESIGN */
        .btn-track {
            background: #0b1d51;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            font-weight: bold;
            font-size: 15px;
            border-radius: 50px; /* Modern rounded look */
            box-shadow: 0px 4px 6px rgba(0,0,0,0.15);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        /* HOVER INTERACTION STATE */
        .btn-track:hover {
            background: white;
            color: #0b1d51;
            border-color: #0b1d51;
            transform: translateY(-2px); /* Subtle click-me lift animation */
            box-shadow: 0px 6px 12px rgba(0,0,0,0.25);
        }

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
        <h1>Course Details</h1>
    </section>

    <div class="action-container">
        <a href="../users/request_courses.php" class="btn-track">
            📋 Request Courses
        </a>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Duration</th>
                <th>Fee</th>
            </tr>

            <?php
            $querry = "SELECT * FROM courses";
            $result = mysqli_query($conn, $querry);

            while($row = mysqli_fetch_assoc($result)){
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                    <td><?php echo number_format($row['fee']); ?></td>
                </tr>
            <?php
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

</body>
</html>