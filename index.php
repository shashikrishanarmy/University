<!DOCTYPE html>
<html>

<head>
    <title>NEXUS UNIVERSITY</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background-color: #7480b5ff;
        }

        nav {
            background: #0b1d51;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

       
        nav a{
            color:white;
            text-decoration:none;
            margin:10px;

        }

        nav a:hover{
            color:gold;
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

        .card-container {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            align-items: stretch; /* IMPORTANT */
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
            padding: 25px 40px;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-left {
            text-align: left;
        }

        .footer-center {
            text-align: center;
            flex: 1;
        }

        .footer-left p,
        .footer-center p {
            margin: 8px 0;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="background-color: #76e5ebff;">

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

    <section class="hero">

        <h1>Build Your Future With Us</h1>
        

    </section>


  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">

  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
  </div>

  <div class="carousel-inner">

    <div class="carousel-item active">
      <img src="assets/images/uni1.jpeg" class="d-block w-50 mx-auto" alt="First slide">
    </div>

    <div class="carousel-item">
      <img src="assets/images/uni2.jpg" class="d-block w-50 mx-auto" alt="Second slide">
    </div>

    <div class="carousel-item">
      <img src="assets/images/uni3.jpg" class="d-block w-50 mx-auto" alt="Third slide">
    </div>

  </div>

</div>

    <section>

        <h2 style="text-align:center;"> Featured Courses </h2>

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


            <div class="footer-center">

                <p>© 2026 All Rights Reserved</p>

            </div>

        </div>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>