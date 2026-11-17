<?php
session_start();
include("../../includes/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<h2>Please log in to place an order.</h2>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'] ?? '';
    $payment_method = $_POST['payment'];

    // Retrieve cart items
    $cart_query = "SELECT c.book_id, c.quantity, b.price, b.title 
                   FROM cart c
                   JOIN books b ON c.book_id = b.id
                   WHERE c.user_id = $user_id";
    $cart_result = mysqli_query($conn, $cart_query);

    if (!$cart_result || mysqli_num_rows($cart_result) == 0) {
        echo "<h2>Your cart is empty!</h2>";
        exit;
    }

    // Insert one order per book in the cart
    while ($row = mysqli_fetch_assoc($cart_result)) {
        $book_id = $row['book_id'];
        $book_name = $row['title'];
        $quantity = $row['quantity'];
        $total_amount = $row['price'] * $quantity;

        $stmt = $conn->prepare("INSERT INTO orders (user_id,book_id, fullname, email, phone, address, city, pincode, payment_method, total_amount, book_name, total_quantity, order_date) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "iisssssssdsi",
            $user_id,
            $book_id,
            $fullname,
            $email,
            $phone,
            $address,
            $city,
            $pincode,
            $payment_method,
            $total_amount,
            $book_name,
            $quantity
        );

        $stmt->execute();
        $stmt->close();
    }

    // Clear user's cart
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");
    $_SESSION['success']="Order placed successfully";
    header("location:../../index.php");

    // echo "<h2>Order placed successfully!</h2>";
    exit;
}
?>
