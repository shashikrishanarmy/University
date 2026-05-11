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
    padding:25px 40px;
}

.footer-container{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
}

.footer-left{
    text-align:left;
}

.footer-center{
    text-align:center;
    flex:1;
}

.footer-left p,
.footer-center p{
    margin:8px 0;
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
    <h1> Contact Details </h1>
</section>

<div class="table-container">

        <table>

            <tr>
                <th> Contact ID </th>
                <th> Contact Name </th>
                <th> Email </th>
                <th> Message </th>
            </tr>

            <?php

            $querry = "SELECT * FROM contacts";

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
</div>

<footer>

    <div class="footer-container">

        
        <div class="footer-left">

            <h3>Contact Us</h3>

            <p>📞 Call: 0114325690</p>

            <p>✉️ Email: nexusuniversity@gmail.com</p>

            <p>📍 Address: Nexus University, New Kandy Road, Malabe</p>

        </div>

        
        <div class="footer-center">

            <p>© 2026 All Rights Reserved</p>

        </div>

    </div>

</footer>




</body>
</html>
