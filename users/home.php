<?php
include '../config/db.php';
session_start();
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
    margin: 0;
    font-family: Arial;
    background-color: #7480b5ff;
    padding-top: 110px;
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

/* HERO SECTION */
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

/* FEATURE CARDS */
.cards {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
    padding: 30px;
}

.card {
    background: white;
    width: 280px;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
}

.card h3 {
    color: #0b1d51;
}

.card p {
    color: #555;
}

.card a {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 15px;
    background: #0b1d51;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.card a:hover {
    background: #142c7a;
}

/* FOOTER */
footer {
    background: #0b1d51;
    color: white;
    padding: 20px;
}

/* TOP ROW */
.footer-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* LEFT SIDE */
.footer-left p {
    margin: 5px 0;
}

/* RIGHT SIDE (MAP) */
.map-container iframe {
    width: 300px;
    height: 200px;
}

/* BOTTOM */
.footer-bottom {
    text-align: center;
    margin-top: 15px;
    border-top: 1px solid #ffffff33;
    padding-top: 10px;
}

    </style>
</head>

<body>

<!-- NAVBAR -->
<nav>
    <div class="logo-section">
        <img src="../assets/images/logo.png" alt="Logo" class="logo">
        <h2 style="color:white;">NEXUS UNIVERSITY</h2>
    </div>

    <div>
        <a href="../users/home.php" style="color:gold;">HOME</a>
        <a href="../users/view_materials.php">MATERIALS</a>
        <a href="../users/assignments.php">ASSIGNMENTS</a>
        <a href="../users/view_timetable.php">TIMETABLE</a>
        <a href="../auth/logout.php">LOGOUT</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <h1>Welcome to Nexus University</h1>
    <p>Explore courses, access study materials, and enhance your learning experience.</p>
</section>

<!-- FEATURE CARDS -->
<section class="cards">

    <div class="card">
        <h3>📚 Courses</h3>
        <p>View all available courses and enroll easily.</p>
        <a href="../users/courses.php">Go to Courses</a>
    </div>

    <div class="card">
        <h3>📄 Materials</h3>
        <p>Download lecture notes and study resources.</p>
        <a href="../users/view_materials.php">View Materials</a>
    </div>

    <div class="card">
        <h3>🎓 My Learning</h3>
        <p>Track your academic progress and activities.</p>
        <a href="#">Coming Soon</a>
    </div>

</section>

<!-- FOOTER -->
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