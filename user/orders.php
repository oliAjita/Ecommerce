<?php
session_start();
include("../includes/db.php");
include("../includes/header.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<h2>Please log in to view your orders.</h2>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders for this user
$query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="/ECOMMERCE/assets/css/cart/checkout.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        h2 {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>My Orders</h2>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Book Name</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Order Date</th>
            </tr>
            <?php while($order = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['book_name']); ?></td>
                    <td><?php echo $order['total_quantity']; ?></td>
                    <td>Rs <?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php include("../includes/footer.php"); ?>
