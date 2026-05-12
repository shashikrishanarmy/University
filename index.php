<!DOCTYPE html>
<html>

<head>
    <title>NEXUS UNIVERSITY</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
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

       
        nav a{
            color:white;
            text-decoration:none;
            margin:10px;

        }

        nav a:hover{
            color:gold;
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

        .hero h1 {
            font_size: 50px;
            color: #0b1d51;

        }

        .btn {
            background: #0b1d51;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;

        }

        carouselExampleIndicators {
            margin-top: 40px;
        }

        .carousel-item img {
            width: 90%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .featured-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #10100fff;
            font-family: 'Poppins', sans-serif;
            margin-top: 10px;
            margin-bottom: 5px;
            letter-spacing: 1px;
            position: relative;
            animation: zoomIn 0.8s ease-out forwards;
}

        @keyframes zoomIn {
            from {
            transform: scale(0.7);
            opacity: 0;
            }
            to {
            transform: scale(1);
            opacity: 1;
            }
        }
        

        

        .card-container {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            align-items: stretch;
            margin: 20px;
        }

        .card {
            flex: 1;              /* to keeps equal width of the cards */
            padding: 20px;
            background: #4dd2faff;
            border-radius: 10px;
            text-align: center;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;

            min-height: 220px;   /* to keeps equal height of the cards */
        }

        .card-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin: 10px 0;
            border-radius: 8px;
        }

        
    


        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

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

/* BOTTOM CENTER */
.footer-bottom {
    text-align: center;
    margin-top: 15px;
    border-top: 1px solid #ffffff33;
    padding-top: 10px;
}
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

</head>

<body style="background-color: #76e5ebff;">

    <nav>
        <div class="logo-section">
        <img src="assets/images/logo.png" alt="NEXUS UNIVERSITY Logo" class="logo">
        <h2 style="color:white;">NEXUS UNIVERSITY</h2>
        </div>

        <div>
            <a href="index.php">HOME</a>
            <a href="./user/courses.php">COURSES</a>
            <!-- <a href="gallery.php">GALLERY</a> -->
            <!-- <a href="contact.php">CONTACT US</a> -->

            <a href="auth/login.php">LOGIN</a>
            <!-- <a href="auth/signup.php">SIGNUP</a> -->
        </div>
    </nav>

    


  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">

  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
  </div>

  <div class="carousel-inner">

    <div class="carousel-item active">
      <img src="assets/images/uni1.jpeg" class="d-block w-80 mx-auto" alt="First slide">
    </div>

    <div class="carousel-item">
      <img src="assets/images/uni2.jpg" class="d-block w-80 mx-auto" alt="Second slide">
    </div>

    <div class="carousel-item">
      <img src="assets/images/uni3.jpg" class="d-block w-80 mx-auto" alt="Third slide">
    </div>

  </div>

</div>

    <section>
        <div class="featured-title p-3">
        <h2 class="featured-title" style="text-align:left; font-weight: bold;"> Professional Courses </h2>
         </div>
        </div>

<?php
include 'config/db.php';

$result = mysqli_query($conn, "SELECT * FROM home_sections");
?>

<div class="card-container">

<?php if(mysqli_num_rows($result) > 0){ ?>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <div class="card">

            <h3><?php echo htmlspecialchars($row['title']); ?></h3>

            <img src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" class="card-img">

            <p style="text-align:justify;">
                <?php echo htmlspecialchars($row['description']); ?>
            </p>

        </div>

    <?php } ?>

<?php } else { ?>

    <p style="text-align:center; width:100%;">
        No courses available at the moment.
    </p>

<?php } ?>

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
                src="https://www.google.com/maps/embed?pb=..."
                style="border:0;" 
                loading="lazy">
            </iframe>
        </div>

    </div>

    
    <div class="footer-bottom">
        <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>
    </div>

</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>