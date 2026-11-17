<?php
session_start();
include(__DIR__ . "/../../includes/db.php");

$base_url = "/ECOMMERCE";
$isLoggedIn = isset($_SESSION['user_id']);

$search = "";

if (isset($_GET['query'])) {
    $search = trim($_GET['query']);
}
?>

<?php include(__DIR__ . "/../../includes/header.php"); ?>

<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/book_card.css">

<div class="book-container">

<?php

if (!empty($search)) {

    // ✅ Prepared statement for safe search
    $stmt = $conn->prepare("
        SELECT * FROM books 
        WHERE title LIKE ? 
        OR author LIKE ?
        ORDER BY id DESC
    ");

    $like = "%{$search}%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $in_stock = $row['stock'] > 0;
?>

        <!-- ✅ SAME BOOK CARD AS YOUR HOME PAGE -->
        <div class="book-card"
            onclick="window.location='<?php echo $base_url; ?>/pages/products/product_detail.php?id=<?php echo $row['id']; ?>'">

            <div class="book-image">
                <img src="<?php echo $base_url; ?>/admin/images/<?php echo $row['image']; ?>" alt="Book Image">

                <span class="price-tag">
                    Rs. <?php echo $row['price']; ?>
                </span>

                <?php if (!$in_stock) { ?>
                    <span class="out-of-stock-tag">Out of Stock</span>
                <?php } ?>
            </div>

            <div class="book-info">
                <h3 class="book-title">
                    <?php echo $row['title']; ?>
                </h3>

                <div class="book-buttons">

                    <?php if ($isLoggedIn) { ?>
                        <a href="<?php echo $base_url; ?>/pages/cart/add_to_cart.php?id=<?php echo $row['id']; ?>"
                            onclick="event.stopPropagation();">
                            <button class="btn-cart"
                                <?php echo $in_stock ? "" : "disabled style='opacity:0.5; cursor:not-allowed;'"; ?>>
                                Add to Cart
                            </button>
                        </a>
                    <?php } ?>

                    <a href="<?php echo $base_url; ?>/pages/products/product_detail.php?id=<?php echo $row['id']; ?>"
                        onclick="event.stopPropagation();">
                        <button class="btn-details">View Details</button>
                    </a>

                </div>
            </div>

        </div>

<?php
        }

    } else {
        echo "<h3 style='padding:20px;'>No books found for <b>" . htmlspecialchars($search) . "</b></h3>";
    }

} else {
    echo "<h3 style='padding:20px;'>Enter a search keyword.</h3>";
}
?>

</div>

<?php include(__DIR__ . "/../../includes/footer.php"); ?>
