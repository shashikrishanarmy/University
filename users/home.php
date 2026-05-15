<!DOCTYPE html>
<html>

<head>

    <script>
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.location.href = "../index.php";
    };
    </script>

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



    /* TOP BAR (mobile) */
.topbar {
    display: none;
    background: #0b1d51;
    color: white;
    padding: 12px 15px;
    align-items: center;
    justify-content: space-between;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 2000;
}

.menu-btn {
    font-size: 24px;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
}



.sidebar h2 {
    color: white;
    text-align: center;
}

.sidebar a {
    display: block;
    color: white;
    padding: 12px;
    text-decoration: none;
}

.sidebar a:hover {
    background: #1f3a93;
}





/* RESPONSIVE */
@media (max-width: 768px) {

    

    body {
        margin-left: 0;
        padding-top: 60px;
    }
}
}
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

</head>

    

<body style="background-color: #76e5ebff;">

    <nav>
        
        <div class="logo-section">
            <button class="btn btn-light" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"> ☰ Menu </button>
        <img src="../assets/images/logo.png" alt="NEXUS UNIVERSITY Logo" class="logo">
        <h2 style="color:white;">NEXUS UNIVERSITY</h2>
        </div>

        <div>
            <a href="../index.php">HOME</a>
            <a href="./users/courses.php">COURSES</a>
            <!-- <a href="gallery.php">GALLERY</a> -->
            <!-- <a href="contact.php">CONTACT US</a> -->

            <a href="auth/login.php">LOGIN</a>
            <a href="../auth/logout.php">LOGOUT</a>
            <!-- <a href="auth/signup.php">SIGNUP</a> -->
        </div>
    </nav>

   <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas">

  <div class="offcanvas-header">
    <h5 class="offcanvas-title">NEXUS UNIVERSITY</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body">
    <a href="index.php" class="d-block py-2">HOME</a>
    <a href="./users/courses.php" class="d-block py-2">COURSES</a>
    <a href="auth/login.php" class="d-block py-2">LOGIN</a>
  </div>

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

    <script>
        function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const overlay = document.querySelector(".overlay");

            if (sidebar.style.left === "0px") {
            sidebar.style.left = "-220px";
            overlay.style.display = "none";
        }   else {
            sidebar.style.left = "0px";
            overlay.style.display = "block";
            }
        }
</script>
</body>

</html>