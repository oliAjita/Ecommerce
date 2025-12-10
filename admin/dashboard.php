<h1>Admin dashboard</h1>
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // user is NOT admin
    header("Location: ../user/login.php");
    exit();
}
?>