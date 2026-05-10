<?php

session_start();

if(!isset($_SESSION['user_id'])){

    header('Location: ../auth/login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>

<body>

    <h1> Welcome to Admin Dashboard </h1>

    <a href="manage-course.php"> Manage Courses </a>

</body>
</html>