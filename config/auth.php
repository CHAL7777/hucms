<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || !isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}
