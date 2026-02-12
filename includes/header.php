<?php
include("db.php");

// Fetch all categories
$cat_query = "SELECT * FROM categories ORDER BY name ASC";
$cat_result = mysqli_query($conn, $cat_query);
?>

<link rel="stylesheet" href="/ECOMMERCE/assets/css/header.css">

<header class="main-header">
    <div class="header-container">

        <div class="logo">
            <a href="/ECOMMERCE/index.php">📚 BookStore</a>
        </div>

        <!-- ⭐ CATEGORY DROPDOWN (Dynamic) -->
        <div class="category-dropdown">
            <select onchange="window.location.href='category.php?id=' + this.value">
                <option value="">Shop by Category</option>
                <?php while ($cat = mysqli_fetch_assoc($cat_result)) { ?>
                    <option value="<?php echo $cat['id']; ?>">
                        <?php echo $cat['name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <nav class="nav-links">
            <a href="/ECOMMERCE/index.php">Home</a>
            <a href="/ECOMMERCE/cart.php">Cart</a>
            <a href="/ECOMMERCE/user/login.php">Login</a>
        </nav>

    </div>
</header>