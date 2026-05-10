<?php
session_start();
include '../config/db.php';

$error = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];

            header('Location: ../admin/dashboard.php');
            exit();

        } else {
            $error = "Incorrect password";
        }

    } else {
        $error = "Email not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>

<body>

    <h2> Login </h2>

    <form method = "POST">

        <input type="email" name="email" placeholder="Email"> <br><br>

        <input type="password" name="password" placeholder="Password"> <br><br>

        <button type="submit" name="login"> Login </button>

    </form>
    
    <?php if(!empty($error)) : ?>
    <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>