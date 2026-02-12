<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login first!";
    header("Location: ../user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_GET['id']);

// Check if item already exists in cart
$check_query = "SELECT * FROM cart WHERE user_id='$user_id' AND book_id='$book_id'";
$result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($result) > 0) {
    // If exists → increase quantity
    $update_query = "UPDATE cart 
                     SET quantity = quantity + 1 
                     WHERE user_id='$user_id' AND book_id='$book_id'";
    mysqli_query($conn, $update_query);
} else {
    // If not exists → insert new row
    $insert_query = "INSERT INTO cart (user_id, book_id, quantity) 
                     VALUES ('$user_id', '$book_id', 1)";
    mysqli_query($conn, $insert_query);
}

$_SESSION['success'] = "Item successfully added to cart!";
header("Location: cart.php");
exit;
?>