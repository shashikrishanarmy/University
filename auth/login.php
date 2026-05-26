
<?php
session_start();
//include '../middleware/admin_auth.php';
include '../config/db.php';

$error = "";

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // ROLE BASED REDIRECT 
            if($user['role'] == 'admin'){
                header("Location: ../admin/dashboard.php");
            }else{
                header("Location: ../users/home.php");
            }

            exit();

        }else{
            $error = "Incorrect password";
        }

    }else{
        $error = "Email not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NEXUS UNIVERSITY - Login</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg, #0b1d51, #1e3c72);
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

        .login-container{
            width:380px;
            background:white;
            padding:40px;
            border-radius:15px;
            box-shadow:0px 8px 25px rgba(0,0,0,0.3);
            text-align:center;
        }

        .login-container h1{
            color:#0b1d51;
            margin-bottom:10px;
        }

        .login-container p{
            color:gray;
            margin-bottom:30px;
        }

        .input-box{
            margin-bottom:20px;
            text-align:left;
        }

        .input-box label{
            display:block;
            margin-bottom:8px;
            color:#333;
            font-weight:bold;
        }

        .input-box input{
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:8px;
            outline:none;
            transition:0.3s;
        }

        .input-box input:focus{
            border-color:#0b1d51;
            box-shadow:0px 0px 8px rgba(11,29,81,0.3);
        }

        .login-btn{
            width:100%;
            padding:12px;
            border:none;
            border-radius:8px;
            background:#0b1d51;
            color:white;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        .login-btn:hover{
            background:gold;
            color:#0b1d51;
            font-weight:bold;
        }

        .error{
            background:#ffe5e5;
            color:red;
            padding:10px;
            border-radius:8px;
            margin-bottom:15px;
        }

        .footer-text{
            margin-top:20px;
            font-size:14px;
            color:gray;
        }

        .footer-text a{
            color:#0b1d51;
            text-decoration:none;
            font-weight:bold;
        }

        .footer-text a:hover{
            color:gold;
        }

    </style>
</head>

<body>

    <nav>
        <div class="logo-section">
        <img src="../assets/images/logo.png" alt="NEXUS UNIVERSITY Logo" class="logo">
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


    <div class="login-container">

        <h1>NEXUS UNIVERSITY</h1>
        <p>Login to your account</p>

        <?php if(!empty($error)) : ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-box">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-box">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" name="login" class="login-btn">
                Login
            </button>

        </form>

       

    </div>

</body>
</html>