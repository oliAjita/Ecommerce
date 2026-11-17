<?php
session_start();
include("../../includes/db.php");

// ✅ Check if admin logged in
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error'] = "Please log in as admin first!";
    header("Location: ../user/login.php");
    exit;
}

// ✅ Validate parameters
if (!isset($_GET['action']) || !isset($_GET['order_id'])) {
    $_SESSION['error'] = "Invalid request!";
    header("Location: orders.php");
    exit;
}

$order_id = intval($_GET['order_id']);
$action   = strtolower(trim($_GET['action']));

// ✅ Allow ONLY these statuses
$allowed = ['accepted', 'rejected'];

if (!in_array($action, $allowed)) {
    $_SESSION['error'] = "Invalid status!";
    header("Location: orders.php");
    exit;
}

// ✅ If accepted → reduce book stock
if ($action == 'accepted') {

    // Get book_id and quantity from order
    $getOrder = $conn->prepare("SELECT book_id, total_quantity FROM orders WHERE id=?");
    $getOrder->bind_param("i", $order_id);
    $getOrder->execute();
    $result = $getOrder->get_result();
    $order = $result->fetch_assoc();

    $book_id = $order['book_id'];
    $quantity = $order['total_quantity'];

    // Reduce stock
    $reduceStock = $conn->prepare("UPDATE books SET stock = stock - ? WHERE id=?");
    $reduceStock->bind_param("ii", $quantity, $book_id);
    $reduceStock->execute();
}

// ✅ Update order status
$stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("si", $action, $order_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Order status updated successfully!";
} else {
    $_SESSION['error'] = "Failed to update order!";
}

$stmt->close();

// ✅ Redirect back to orders page
header("Location: ../dashboard.php?page=orders");
exit;
?>
