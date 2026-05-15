<?php
session_start();

/* 1. Clear all session variables */
$_SESSION = array();

/* 2. Destroy session data on server */
session_destroy();

/* 3. Delete session cookie */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* 4. Redirect to home page */
header("Location: ../index.php");
exit();
?>