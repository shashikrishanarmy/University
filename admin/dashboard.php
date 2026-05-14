<?php
session_start();
include '../config/db.php';

// TEMP admin access 
if(!isset($_SESSION['admin'])){
    $_SESSION['admin'] = true;
}

/* ================= ADD ================= */
if(isset($_POST['add'])){

    $title = $_POST['title'];
    $desc  = $_POST['description'];

    $image = $_FILES['image']['name'];
    $tmp   = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "../assets/images/".$image);

    mysqli_query($conn, "INSERT INTO home_sections (title, description, image)
    VALUES ('$title','$desc','$image')");
}

/* ================= DELETE ================= */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM home_sections WHERE id=$id");
}

/* ================= UPDATE ================= */
if(isset($_POST['update'])){

    $id    = $_POST['id'];
    $title = $_POST['title'];
    $desc  = $_POST['description'];

    if($_FILES['image']['name']){
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/".$image);

        mysqli_query($conn, "UPDATE home_sections 
        SET title='$title', description='$desc', image='$image'
        WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE home_sections 
        SET title='$title', description='$desc'
        WHERE id=$id");
    }
}

/* ================= FETCH ================= */
$result = mysqli_query($conn, "SELECT * FROM home_sections");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .container {
            width: 90%;
            margin: auto;
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

        .form-box {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
        }

        button {
            background: #0b1d51;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        .card-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 15px;
            width: 30%;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .actions {
            margin-top: 10px;
        }

        .actions a {
            color: red;
            text-decoration: none;
            margin-right: 10px;
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
            <a href="../index.php">HOME</a>
            <a href="../users/courses.php">COURSES</a>
            <!-- <a href="gallery.php">GALLERY</a> -->
            <!-- <a href="contact.php">CONTACT US</a> -->

            <a href="../auth/login.php">LOGIN</a>
            <!-- <a href="auth/signup.php">SIGNUP</a> -->
        </div>
    </nav>

<div class="container">

<h2>Admin Dashboard</h2>

<!-- ADD FORM -->
<div class="form-box">
    <h3>Add New Course</h3>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Title" required>

        <textarea name="description" placeholder="Description" required></textarea>

        <input type="file" name="image" required>

        <button name="add">Add Course</button>
    </form>
</div>

<!-- DISPLAY CARDS -->
<div class="card-container">

<?php while($row = mysqli_fetch_assoc($result)) { ?>

    <div class="card">

        <!-- EDIT FORM INSIDE CARD -->
        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <input type="text" name="title" value="<?php echo $row['title']; ?>">

            <img src="../assets/images/<?php echo $row['image']; ?>">

            <textarea name="description"><?php echo $row['description']; ?></textarea>

            <input type="file" name="image">

            <button name="update">Update</button>

        </form>

        <div class="actions">
            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this?')">Delete</a>
        </div>

    </div>

<?php } ?>

</div>

</div>

</body>
</html>