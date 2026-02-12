<?php
session_start();
include("../../includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['id']);

$query = "DELETE FROM cart WHERE user_id='$user_id' AND book_id='$book_id'";
mysqli_query($conn, $query);

header("Location: cart.php");
exit;
?>