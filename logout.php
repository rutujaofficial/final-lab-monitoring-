<?php
// Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
// Smart Digital Lab Monitoring System - Session Logout

session_start();
$_SESSION = array();

// If session cookie exists, destroy it
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_unset();
session_destroy();

header("Location: index.php");
exit();
?>
