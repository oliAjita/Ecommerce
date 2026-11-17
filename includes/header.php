<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("db.php");
include("config.php");

// Fetch all categories
$cat_query = "SELECT * FROM categories ORDER BY name ASC";
$cat_result = mysqli_query($conn, $cat_query);

// Only check user session
$isLoggedIn = isset($_SESSION['user_id']);
?>

<link rel="stylesheet" href="/ECOMMERCE/assets/css/header.css">

<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <a href="/ECOMMERCE/index.php">📚 BookStore</a>
        </div>

        <!-- //search bar  -->
        <form action="<?php echo BASE_URL; ?>/pages/search/search_function.php" method="GET"
            style="display:flex; gap:5px; margin-left:50px;">
            <input type="text" name="query" placeholder="Search books or authors..." required
                style="padding:8px 12px; border-radius:6px; border:1px solid #ccc;">
            <button type="submit"
                style="padding:6px 12px; background:#333; color:white; border:none; border-radius:6px; cursor:pointer;">
                Search
            </button>
        </form>


        <!-- CATEGORY DROPDOWN -->
        <div class="category-dropdown">
            <select
                onchange="window.location.href='<?= BASE_URL ?>/pages/category/category_filter.php?id=' + this.value">
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
            <a href="/ECOMMERCE/pages/cart/cart.php">Cart</a>

            <?php if ($isLoggedIn) { ?>
                <div class="profile-menu">
                    <div class="profile-trigger" onclick="toggleDropdown()">
                        <span class="profile-icon">👤</span>
                        <span class="profile-name">
                            <?php echo $_SESSION['name']; ?> ▼
                        </span>
                    </div>

                    <div class="dropdown-menu" id="profileDropdown">
                        <a href="/ECOMMERCE/user/profile.php">Profile</a>
                        <a href="/ECOMMERCE/user/orders.php">My Orders</a>
                        <a href="/ECOMMERCE/user/logout.php">Logout</a>
                    </div>
                </div>
            <?php } else { ?>
                <a href="/ECOMMERCE/user/login.php">Login</a>
            <?php } ?>
        </nav>
    </div>
</header>

<script>
    function toggleDropdown() {
        document.getElementById("profileDropdown").classList.toggle("show");
    }

    window.onclick = function (event) {
        if (!event.target.closest('.profile-menu')) {
            document.getElementById("profileDropdown").classList.remove("show");
        }
    }
</script>