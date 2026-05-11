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

        .hero h1{
            font_size:50px;
            color:#0b1d51;

        }

        .btn{
            background:#0b1d51;
            color:white;
            padding:15px 25px;
            border:none;
            border-radius:5px;
            cursor:pointer;

        }

        .card{
            width:300px;
            border:1px solid #ddd;
            padding:20px;
            border-radius:10px;

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

<section class="hero">

        <h1>NEXUS UNIVERSITY</h1>
        <p>Build Your Future With Us</p>
        
        <button class="btn">Explore Courses</button>

</section>

<section>

    <h2 style="text-align:center;"> Featured Courses </h2>

    <div class="courses">

        <div class="card">
            <h3>Web Development</h3>
            <p>Learn Web and Software Development </p>
        </div>

        <div class="card">
            <h3>Cyber Security</h3>
            <p>Learn Ethical Hacking and Security Systems </p>
        </div>

        <div class="card">
            <h3>Data Science</h3>
            <p>Learn AI and Machine Learning</p>
        <div>
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
</body>
</html>
