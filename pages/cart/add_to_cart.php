<?php
session_start();
include(__DIR__ . "/../includes/db.php");
$book_id = $_GET['id'];


// Initialize cart if not created

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
//If book already in cart → increase qty
if (isset($_SESSION['cart'][$book_id])) {
    $_SESSION['cart'][$book_id] += 1;
} else {
    $_SESSION['cart'][$book_id] = 1;
}


$_SESSION['success'] = "Item successfully added to cart!";
header("Location: cart.php");
exit;
?>