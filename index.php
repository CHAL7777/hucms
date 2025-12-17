<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user'])) {
    header("Location: public/dashboard.php");
} else {
    header("Location: public/login.php");
}
exit();
?>
}