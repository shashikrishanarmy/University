<?php

include '../config/db.php';

if(isset($_POST['register'])){

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

    $query = "INSERT INTO users(fullname,email,password) VALUES('$fullname', '$email', '$password')";

    mysqli_query($conn, $query);

    echo "Registration Successfull";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title> SIGNUP </title>
</head>

<body>

<h2> SIGNUP </h2>

<form method ="POST">

    <input type = "text" name = "fullname" placeholder="FullName"> <br><br>

    <input type = "email" name = "email" placeholder= "Email"> <br><br>

    <input type = "password" name = "password" placeholder= "Password"> <br><br>

    <button type = "submit" name = "register"> Register </button>

</form>
</body>
</html>