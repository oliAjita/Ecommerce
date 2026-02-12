<?php
// session_start();
include(__DIR__ . "/db.php");

$base_url = "/ECOMMERCE";

$isLoggedIn = isset($_SESSION['user_id']);

$query = "SELECT * FROM books ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/book_card.css">

<div class="book-container">

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <!-- ✅ Entire Card Clickable -->
        <div class="book-card"
            onclick="window.location='<?php echo $base_url; ?>/pages/products/product_detail.php?id=<?php echo $row['id']; ?>'">

            <div class="book-image">
                <img src="<?php echo $base_url; ?>/admin/images/<?php echo $row['image']; ?>" alt="Book Image">

                <span class="price-tag">
                    Rs.
                    <?php echo $row['price']; ?>
                </span>
            </div>

            <div class="book-info">
                <h3 class="book-title">
                    <?php echo $row['title']; ?>
                </h3>

                <div class="book-buttons">

                    <?php if ($isLoggedIn) { ?>
                        <!-- Show Add to Cart ONLY if logged in -->
                        <a href="<?php echo $base_url; ?>/pages/cart/add_to_cart.php?id=<?php echo $row['id']; ?>"
                            onclick="event.stopPropagation();">
                            <button class="btn-cart">Add to Cart</button>
                        </a>
                    <?php } ?>

                    <!-- View Details (always visible) -->
                    <a href="<?php echo $base_url; ?>/pages/products/product_detail.php?id=<?php echo $row['id']; ?>"
                        onclick="event.stopPropagation();">
                        <button class="btn-details">View Details</button>
                    </a>

                </div>
            </div>

        </div>

    <?php } ?>

</div>