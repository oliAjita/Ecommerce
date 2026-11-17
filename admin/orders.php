<?php
// session_start();
include("../includes/db.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "<h2>Please log in as admin to view orders.</h2>";
    exit;
}

// Fetch all orders
$query = "SELECT * FROM orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Orders - Admin</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            color: #fff;
        }

        .btn-accept {
            background-color: green;
        }

        .btn-reject {
            background-color: red;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-accept {
            color: green;
            font-weight: bold;
        }

        .status-reject {
            color: red;
            font-weight: bold;
        }

        .status-cancelled {
            color: #888;
            font-weight: bo
    </style>
</head>

<body>

    <div class="checkout-container">
        <h2>All Orders</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>User Name</th>

                    <th>Book Name</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo $order['user_id']; ?></td>
                        <td><?php echo $order['fullname']; ?></td>
                        <td><?php echo htmlspecialchars($order['book_name']); ?></td>
                        <td><?php echo $order['total_quantity']; ?></td>
                        <td>Rs <?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                        <td class="
        <?php
        if ($order['status'] == 'pending')
            echo 'status-pending';
        elseif ($order['status'] == 'accepted')
            echo 'status-accept';
        elseif ($order['status'] == 'rejected')
            echo 'status-reject';
        elseif ($order['status'] == 'cancelled')
            echo 'status-cancelled';
        ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </td>
                        <td><?php echo $order['order_date']; ?></td>
                        <td>
                            <?php if ($order['status'] === 'pending'): ?>
                                <a class="btn btn-accept"
                                    href="actions/admin_orders.php?action=accepted&order_id=<?php echo $order['id']; ?>">Accept</a>
                                <a class="btn btn-reject"
                                    href="actions/admin_orders.php?action=rejected&order_id=<?php echo $order['id']; ?>">Reject</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>

    </div>

</body>

</html>