<?php
session_start();
include("../../includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_GET['id']);

// 1. Get the quantity of this book in the cart
$cart_query = mysqli_query($conn, "SELECT quantity FROM cart WHERE user_id='$user_id' AND book_id='$book_id'");
if ($cart_query && mysqli_num_rows($cart_query) > 0) {
    $cart_item = mysqli_fetch_assoc($cart_query);
    $qty_in_cart = intval($cart_item['quantity']);

    // 2. Add that quantity back to stock
    mysqli_query($conn, "UPDATE books SET stock = stock + $qty_in_cart WHERE id='$book_id'");

    // 3. Remove the item from the cart
    mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id' AND book_id='$book_id'");
    
    $_SESSION['success'] = "Item removed from cart and stock updated!";
} else {
    $_SESSION['error'] = "Item not found in cart!";
}

header("Location: cart.php");
exit;
?>
