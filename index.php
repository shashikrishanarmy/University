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
            padding: 20px 40px;
            margin-top: 50px;
            position: relative;
        }

        .footer-container {
            display: flex;
            flex-direction: column;  
            align-items: center;
            justify-content: center;
        }

        .footer-left {
            text-align: center;
            margin-bottom: 15px;
        }

        .footer-center {
            text-align: center;
        }

        .footer-left p,
        .footer-center p {
            margin: 8px 0;
        }

        .map-container {
            position: absolute;
            bottom: 20px;
            right: 20px;
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
            <a href="courses.php">COURSES</a>
            <a href="gallery.php">GALLERY</a>
            <a href="contact.php">CONTACT US</a>

            <a href="auth/login.php">LOGIN</a>
            <a href="auth/signup.php">SIGNUP</a>
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

<div class="card-container">

    <div class="card">
        <h3>Web Development</h3>
        <img src="assets/images/web.jpg" alt="Web Development" class="card-img">
        <p style="text-align:justify;">The Web Development course is designed to provide students with a strong foundation in designing, 
            building, and maintaining modern websites and web applications. This course covers both front-end and back-end development, 
            including essential technologies such as HTML, CSS, JavaScript, and server-side programming concepts. Students will also gain 
            practical experience in responsive design, database integration, and web application deployment. By the end of the course, 
            learners will be able to create fully functional, user-friendly, and dynamic websites that meet industry standards and 
            real-world requirements</p>
    </div>

    <div class="card">
        <h3>Cyber Security</h3>
        <img src="assets/images/cs.jpg" alt="Cyber Security" class="card-img">
        <p style="text-align: justify;">The Cyber Security course provides students with essential knowledge and practical skills to protect computer systems, networks, 
            and data from cyber threats. It covers key areas such as ethical hacking, network security, cryptography, malware analysis, and 
            risk management. Students will learn how cyber attacks occur and how to prevent them using modern security tools and techniques. 
            This course prepares learners to identify vulnerabilities, secure digital systems, and respond effectively to security incidents
             in real-world environments.</p>
    </div>

    <div class="card">
        <h3>Data Science</h3>
        <img src="assets/images/ds.jpg" alt="Data Science" class="card-img">
        <p style="text-align: justify;">The Data Science course introduces students to the principles and techniques used to collect, 
            analyze, and interpret large sets of data to support decision-making. It covers key areas such as statistics, data visualization,
             machine learning, and programming with tools like Python and R. Students will learn how to clean and process data, build 
             predictive models, and extract meaningful insights from complex datasets. This course prepares learners to apply data-driven 
             solutions in various industries such as business, healthcare, and technology.</p>
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
        </div>

        <div class="footer-container">


            <div class="footer-center">

                <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>

            </div>

            <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1806.7150936451546!2d79.95011974610354!3d6.889292134643838!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae251802c0af99d%3A0x335d8c2ab966f3a2!2sFoot%20Path!5e0!3m2!1sen!2slk!4v1778496791443!5m2!1sen!2slk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        </div>

        

        

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>