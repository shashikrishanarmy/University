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

        .title{
            text-align:center;
            padding:30px;
        }

      .gallery-container{
            width:90%;
            margin:auto;
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(280px,1fr));
            gap:25px;
            padding-bottom:50px;
        }

        .gallery-card{
            background:white;
            border-radius:15px;
            overflow:hidden;
            box-shadow:0px 4px 10px rgba(0,0,0,0.1);
            transition:0.3s;
        }

        .gallery-card:hover{
            transform:translateY(-5px);
        }

        .gallery-card img{
            width:100%;
            height:220px;
            object-fit:cover;
        }

        .gallery-card h3{
            text-align:center;
            padding:15px;
            color:#0b1d51;
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

    <h1>University Gallery</h1>

</section>

<div class="gallery-container">

<?php

$query = "SELECT * FROM gallery";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){

?>

    <div class="gallery-card">

        <img src="../assets/images/<?php echo $row['image']; ?>" alt="Gallery Image">

        <h3><?php echo $row['title']; ?></h3>

    </div>

<?php
}
?>

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
