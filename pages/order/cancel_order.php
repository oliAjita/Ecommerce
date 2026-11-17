<?php
session_start();
include("../../includes/db.php");

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$order_id = intval($_GET['id']);

//    1. GET ORDER DETAILS*/
$stmt = $conn->prepare("SELECT book_id, total_quantity, status FROM orders WHERE id=? AND user_id=?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Order not found");
}


// 2. ONLY ALLOW CANCEL IF PENDING
if ($order['status'] !== 'pending') {
    die("Order cannot be cancelled");
}

//    3. UPDATE ORDER STATUS*/
$stmt = $conn->prepare("UPDATE orders SET status='cancelled' WHERE id=?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->close();

//    4. RESTORE BOOK STOCK  ✅ IMPORTANT*/
// $stmt = $conn->prepare("UPDATE books SET stock = stock + ? WHERE id=?");
// $stmt->bind_param("ii", $order['total_quantity'], $order['book_id']);
// $stmt->execute();
// $stmt->close();

//    5. REDIRECT BACK*/
header("Location: ../../user/orders.php");
exit;
