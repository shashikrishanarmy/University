<?php

session_start();

include '../config/db.php';

/* CHECK LOGIN */
if(!isset($_SESSION['user_id'])){

    header("Location: ../auth/login.php");
    exit();

}

/* GET LOGGED-IN STUDENT ID */
$student_id = $_SESSION['user_id'];

/* FETCH ONLY REGISTERED COURSE MATERIALS */

$query = "

SELECT

m.title,
m.file_path,
m.uploaded_at,
c.course_name

FROM course_materials m

JOIN enrollments e
ON m.course_id = e.course_id

JOIN courses c
ON m.course_id = c.id

WHERE e.student_id = '$student_id'

ORDER BY m.id DESC

";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html>

<head>
    <title>NEXUS UNIVERSITY - View Materials</title>

    <style>

        *{
            box-sizing:border-box;
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

        
        .title{
            text-align:center;
            margin-bottom:20px;
        }

        .title h1{
            color:#0b1d51;
            font-size:36px;
        }

        
        .table-container {
            display: flex;
            justify-content: center;
            margin-bottom: 50px;
            flex: 1; 
        }

        table{
            width:85%;
            border-collapse:collapse;
            background:white;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        table th{
            background:#0b1d51;
            color:white;
            padding:15px;
        }

        table td{
            padding:15px;
            text-align:center;
            border:1px solid #ddd;
        }

        table tr:hover{
            background:#f2f2f2;
        }

        
        .btn-download{
            background:#007bff;
            color:white;
            padding:6px 12px;
            border-radius:4px;
            text-decoration:none;
            font-size:14px;
        }

        .btn-download:hover{
            background:#0056b3;
        }

        .empty-msg{
            text-align:center;
            font-size:18px;
            color:white;
            margin-top:30px;
        }

        
        footer {
            background: #0b1d51;
            color: white;
            padding: 20px;
            margin-top: auto; /* Acts as an anchor pushing it to the very bottom */
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


<nav>

    <div class="logo-section">

        <img src="../assets/images/logo.png" class="logo">

        <h2 style="color:white; margin:0;">
            NEXUS UNIVERSITY
        </h2>

    </div>

    <div>

        <a href="../users/home.php" style="color:gold;">STUDENT PANEL</a>
        <a href="../users/view_materials.php">MATERIALS</a>
        <a href="../users/assignments.php">ASSIGNMENTS</a>
        <a href="../users/view_timetable.php">TIMETABLE</a>
        <a href="../auth/logout.php">LOGOUT</a>

    </div>

</nav>


<div class="title">

    <h1>
        My Course Materials
    </h1>

</div>


<div class="table-container">

    <table>

        <tr>

            <th>Course Name</th>

            <th>Material Title</th>

            <th>File Name</th>

            <th>Date Added</th>

            <th>Download</th>

        </tr>

        <?php

        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_assoc($result)){

        ?>

        <tr>

            <td>

                <strong>

                    <?php echo htmlspecialchars($row['course_name']); ?>

                </strong>

            </td>

            <td>

                <?php echo htmlspecialchars($row['title']); ?>

            </td>

            <td>

                <code>

                    <?php echo basename($row['file_path']); ?>

                </code>

            </td>

            <td>

                <?php echo date('M d, Y', strtotime($row['uploaded_at'])); ?>

            </td>

            <td>

                <a
                href="<?php echo $row['file_path']; ?>"
                class="btn-download"
                download>

                    Download

                </a>

            </td>

        </tr>

        <?php

            }

        }else{

            echo "

            <tr>

                <td colspan='5'>

                    No materials available for your registered courses.

                </td>

            </tr>

            ";

        }

        ?>

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
            <iframe src="https://www.google.com/maps/embed?pb=..." loading="lazy" aria-hidden="false"></iframe>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>
    </div>
</footer>

</body>
</html>