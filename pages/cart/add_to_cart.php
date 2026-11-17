<?php
session_start();
include("../../includes/db.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login first!";
    header("Location: ../user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_GET['id']);

// Check current stock
$stock_query = mysqli_query($conn, "SELECT stock FROM books WHERE id='$book_id'");
$book = mysqli_fetch_assoc($stock_query);

if (!$book) {
    $_SESSION['error'] = "Book not found!";
    header("Location: cart.php");
    exit;
}

if ($book['stock'] <= 0) {
    $_SESSION['error'] = "Product out of stock!";
    header("Location: cart.php");
    exit;
}


// Check if item already exists in cart
$check_query = "SELECT * FROM cart WHERE user_id='$user_id' AND book_id='$book_id'";
$result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($result) > 0) {
    // Increase quantity in cart
    mysqli_query($conn, "UPDATE cart 
                         SET quantity = quantity + 1 
                         WHERE user_id='$user_id' AND book_id='$book_id'");
} else {
    // Insert new item in cart
    mysqli_query($conn, "INSERT INTO cart (user_id, book_id, quantity) 
                         VALUES ('$user_id', '$book_id', 1)");
}

$_SESSION['success'] = "Item successfully added to cart!";
header("Location: cart.php");
exit;
?>