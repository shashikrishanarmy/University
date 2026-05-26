<?php
session_start();

/* Clear all session variables */
$_SESSION = array();

/* Destroy session data on server */
session_destroy();

/* Delete session cookie */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* Redirect to home page */
header("Location: ../index.php");
exit();
?>