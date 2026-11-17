<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../includes/db.php");

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized");
}

// --- FETCH QUICK STATS ---

// Total books
$bookQuery = "SELECT COUNT(*) as total_books, SUM(stock) as total_stock FROM books";
$bookResult = mysqli_query($conn, $bookQuery);
if (!$bookResult) {
    die("Book query failed: " . mysqli_error($conn));
}
$bookData = mysqli_fetch_assoc($bookResult);

// Total users
$userQuery = "SELECT COUNT(*) as total_users FROM users";
$userResult = mysqli_query($conn, $userQuery);
if (!$userResult) {
    die("User query failed: " . mysqli_error($conn));
}
$userData = mysqli_fetch_assoc($userResult);

// Total orders
$orderQuery = "SELECT COUNT(*) as total_orders, SUM(total_quantity) as total_ordered FROM orders";
$orderResult = mysqli_query($conn, $orderQuery);
if (!$orderResult) {
    die("Order query failed: " . mysqli_error($conn));
}
$orderData = mysqli_fetch_assoc($orderResult);
?>


<div class="header">
    <h1>Welcome to Admin Dashboard</h1>
    <p>Manage your bookstore from here</p>
</div>

<div class="content-section">
    <h2>Quick Stats</h2>
    <p>Use the sidebar to navigate and manage your store.</p>

    <div class="stats-cards" style="display:flex; gap:20px; flex-wrap:wrap;">
        <!-- Total Books -->
        <div class="card" style="padding:20px; background:#f5f5f5; border-radius:10px; flex:1; min-width:200px;">
            <h3>Total Books</h3>
            <p style="font-size:24px; font-weight:bold;"><?php echo $bookData['total_books'] ?? 0; ?></p>
            <p>In stock: <?php echo $bookData['total_stock'] ?? 0; ?></p>
        </div>

        <!-- Total Users -->
        <div class="card" style="padding:20px; background:#f5f5f5; border-radius:10px; flex:1; min-width:200px;">
            <h3>Total Users</h3>
            <p style="font-size:24px; font-weight:bold;"><?php echo $userData['total_users'] ?? 0; ?></p>
        </div>

        <!-- Total Orders -->
        <div class="card" style="padding:20px; background:#f5f5f5; border-radius:10px; flex:1; min-width:200px;">
            <h3>Total Orders</h3>
            <p style="font-size:24px; font-weight:bold;"><?php echo $orderData['total_orders'] ?? 0; ?></p>
            <p>Total items ordered: <?php echo $orderData['total_ordered'] ?? 0; ?></p>
        </div>
    </div>

    <!-- Optional: Latest Books Table -->
    <h3 style="margin-top:40px;">Latest Books</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Stock</th>
            <th>Category</th>
        </tr>
        <?php
        $latestBooks = mysqli_query($conn, "SELECT b.id, b.title, b.author, b.stock, c.name as category FROM books b LEFT JOIN categories c ON b.category_id = c.id ORDER BY b.id DESC LIMIT 5");
        while ($book = mysqli_fetch_assoc($latestBooks)) {
            echo "<tr>
                    <td>{$book['id']}</td>
                    <td>".htmlspecialchars($book['title'])."</td>
                    <td>".htmlspecialchars($book['author'])."</td>
                    <td>{$book['stock']}</td>
                    <td>".htmlspecialchars($book['category'])."</td>
                  </tr>";
        }
        ?>
    </table>

    <!-- Optional: Latest Orders Table -->
    <h3 style="margin-top:40px;">Latest Orders</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Total Quantity</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
        <?php
        $latestOrders = mysqli_query($conn, "SELECT o.id, u.name as user_name, o.total_quantity, o.status, o.order_date FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5");
        while ($order = mysqli_fetch_assoc($latestOrders)) {
            echo "<tr>
                    <td>{$order['id']}</td>
                    <td>".htmlspecialchars($order['user_name'])."</td>
                    <td>{$order['total_quantity']}</td>
                    <td>".htmlspecialchars($order['status'])."</td>
                    <td>{$order['order_date']}</td>
                  </tr>";
        }
        ?>
    </table>
</div>
