<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}

// Determine current page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Handle logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: /ECOMMERCE/user/login.php");
    exit();
}?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/ECOMMERCE/assets/css/admin/dashboard.css" />
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav class="nav-links">
                <a href="dashboard.php?page=home" class="<?php echo $page == 'home' ? 'active' : ''; ?>">📊 Home</a>
                <!-- <a href="dashboard.php?page=add_book" class="<?php echo $page == 'add_book' ? 'active' : ''; ?>">➕ Add
                    Book</a> -->
                <a href="dashboard.php?page=products" class="<?php echo $page == 'products' ? 'active' : ''; ?>">📚
                    Manage Book</a>
                <a href="dashboard.php?page=categories" class="<?php echo $page == 'categories' ? 'active' : ''; ?>">🏷️
                    Manage Categories</a>
                <a href="dashboard.php?page=orders" class="<?php echo $page == 'orders' ? 'active' : ''; ?>">📋
                    Orders</a>
                <a href="dashboard.php?page=users" class="<?php echo $page == 'users' ? 'active' : ''; ?>">👥 Users</a>
                <div class="logout-btn">
                    <a href="dashboard.php?action=logout">🚪 Logout</a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <?php if ($page == 'home'): ?>
                                        <?php include("home.php"); ?>


            <?php elseif ($page == 'add_book'): ?>
                        <?php include("actions/add_book.php"); ?>


            <?php elseif ($page == 'products'): ?>
                <?php include('actions/products.php'); ?>

                <!-- Add Category Block -->
            <?php elseif ($page == 'categories'): ?>
                <?php include('actions/categories.php'); ?>


                <!-- //orders block
     <?php elseif ($page == 'orders'): ?> <div class="header"> <h1>Manage Orders</h1> </div> <div class="content-section"> 
        <?php include("orders.php"); ?> </div>
                  //up to here -->

            <?php elseif ($page == 'users'): ?>
                        <?php include("actions/users.php"); ?> </div>

            <?php endif; ?>
        </main>
    </div>
</body>

</html>