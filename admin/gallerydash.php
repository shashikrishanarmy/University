
<?php
/*
include 'config/db.php';
*/
?>


<!--
<!DOCTYPE html>
<html>
<head>
    <title>NEXUS UNIVERSITY</title>

    <style>

        body{
            margin: 0;
            font-family: Arial;
            background-color: #7480b5ff;
            padding-top: 30px;
            
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

        .title{
            text-align:center;
            padding:40px 20px;
        }

        .title h1{
            color:#0b1d51;
            font: size 40px;
        }

        .upload-btn{
            display:inline-block;
            margin-bottom:20px;
            padding:12px 20px;
            background:#0b1d51;
            color:white;
            text-decoration:none;
            border-radius:6px;
            font-weight:bold;
        }

        .upload-btn:hover{
            background:#142f7a;
        }

        .edit-btn{
            background:#28a745;
            color:white;
            padding:6px 12px;
            text-decoration:none;
            border-radius:5px;
        }

        .delete-btn{
            background:#dc3545;
            color:white;
            padding:6px 12px;
            text-decoration:none;
            border-radius:5px;
            margin-left:5px;
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
 </head>
 
 <body>

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

<section class="title">
    <h1> Gallery Dashboard </h1>
</section>

<div class="table-container">

    <a href="upload_gallery.php" class="upload-btn">
    + Upload New Image
    </a>

        <table>

            <tr>
                <th> Gallery ID </th>
                <th> Image Title </th>
                <th> Image Description </th>
                <th> Image Path </th>
                <th> Actions </th>
            </tr>

            <?php
            /*

            $querry = "SELECT * FROM gallery";

            $result = mysqli_query($conn, $querry);

            while($row = mysqli_fetch_assoc($result)){
                ?>

                <tr>
                <td> <?php echo $row['id']; ?></td>
                <td> <?php echo $row['title'];?></td>
                <td> <?php echo $row['description']; ?></td>
                <td> <?php echo $row['image']; ?></td>
                <td>
                    <a href="edit_gallery.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_gallery.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
            <?php
            }
*/
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




</body>
</html>

-->
