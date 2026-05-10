<?php

include 'config/db.php';

?>



<!DOCTYPE html>
<html>
<head>
    <title>NEXUS UNIVERSITY</title>

    <style>

        body{
            margin:0;
            font-family:Arial;
        }

        nav{
            background:#0b1d51;
            padding:15px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        nav a{
            color:white;
            text-decoration:none;
            margin:10px;

        }

        nav a:hover{
            color:gold;
        }

        .title{
            text-align:center;
            padding:40px 20px;
        }

        .title h1{
            color:#0b1d51;
            font: size 40px;
        }

        .table-container{
            display:flex;
            justify-content:center;
            margin: bottom 60px;
        }

        table{

            width:80%;
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

            background: #f2f2f2;
        }


        footer{
            background:#0b1d51;
            color:white;
            text-align:center;
            padding:25px;
        }

        footer p{

            margin:5px;
        }

    </style>
 </head>
 
 <body>

 <nav>
    <h2 style="color:white;">NEXUS UNIVERSITY</h2>

    <div>
        <a href="index.php">HOME</a>
        <a href="courses.php">COURSES</a>
        <a href="gallery.php">GALLERY</a>
        <a href="contact.php">CONTACT US</a>

        <a href="auth/login.php">LOGIN</a>
        <a href="auth/signup.php">SIGNUP</a>
    </div>
</nav>

<section class="title">
    <h1> Course Details </h1>
</section>

<div class="table-container">

        <table>

            <tr>
                <th> Course ID </th>
                <th> Course Name </th>
                <th> Duration </th>
                <th> Fee </th>
            </tr>

            <?php

            $querry = "SELECT * FROM courses";

            $result = mysqli_query($conn, $querry);

            while($row = mysqli_fetch_assoc($result)){
                ?>

                <tr>
                <td> <?php echo $row['id']; ?></td>
                <td> <?php echo $row['course_name'];?></td>
                <td> <?php echo $row['duration']; ?></td>
                <td> <?php echo number_format($row['fee']); ?></td>
            </tr>
            <?php
            }

            ?>
        </table>




</body>
</html>
