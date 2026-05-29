<?php
session_start();
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
    font-family:'Segoe UI', sans-serif;
}


body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:url('../assets/images/login.jpg') no-repeat center/cover;
    position:relative;
}

/* 🌑 Overlay */
body::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
}

/* 🧊 Glass Card */
.login-container{
    position:relative;
    z-index:1;
    width:360px;
    padding:40px;
    border-radius:15px;

    background:rgba(255,255,255,0.1);
    backdrop-filter:blur(12px);

    border:1px solid rgba(255,255,255,0.2);
    box-shadow:0 8px 30px rgba(0,0,0,0.5);

    color:white;
    text-align:center;

    animation:fadeIn 1s ease;
}

/* Animation */
@keyframes fadeIn{
    from{opacity:0; transform:translateY(30px);}
    to{opacity:1; transform:translateY(0);}
}

.login-container h1{
    margin-bottom:10px;
}

.login-container p{
    color:#ddd;
    margin-bottom:25px;
}


.input-box{
    margin-bottom:20px;
    text-align:left;
    position:relative;
}

.input-box label{
    font-size:14px;
}

.input-box input{
    width:100%;
    padding:12px;
    margin-top:5px;
    border-radius:8px;
    border:none;
    outline:none;

    background:rgba(255,255,255,0.2);
    color:white;

    transition:0.3s;
}

.input-box input::placeholder{
    color:#ccc;
}

.input-box input:focus{
    background:rgba(255,255,255,0.3);
    box-shadow:0 0 8px rgba(255,255,255,0.5);
}

/* 👁️ Password icon */
.toggle-password{
    position:absolute;
    right:10px;
    top:38px;
    cursor:pointer;
}


.login-btn{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:linear-gradient(45deg, gold, orange);
    color:black;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.login-btn:hover{
    transform:scale(1.05);
    box-shadow:0 0 15px gold;
}


.error{
    background:rgba(255,0,0,0.2);
    color:#ffb3b3;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
}


nav{
    position:fixed;
    top:0;
    width:100%;
    padding:15px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:rgba(89, 88, 88, 0.5);
    backdrop-filter:blur(8px);
}

nav a{
    color:white;
    text-decoration:none;
    margin:10px;
    transition:0.3s;
}

nav a:hover{
    color:gold;
}

.logo-section{
    display:flex;
    align-items:center;
    gap:10px;
}

.logo{
    width:50px;
    height:50px;
    border-radius:50%;
}
</style>

</head>

<body>

<nav>
    <div class="logo-section">
        <img src="../assets/images/logo.png" class="logo">
        <h2>NEXUS UNIVERSITY</h2>
    </div>

    <div>
        <a href="../index.php">HOME</a>
        <a href="../users/courses.php">COURSES</a>
        <a href="../auth/login.php">LOGIN</a>
    </div>
</nav>

<div class="login-container">

    <h1>NEXUS UNIVERSITY</h1>
    <p>Login to your account</p>

    <?php if(!empty($error)) : ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="input-box">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="input-box">
            <label>Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>

        <button type="submit" name="login" class="login-btn">
            Login
        </button>

    </form>

</div>

<script>
function togglePassword(){
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>