<?php
include(__DIR__ . "/db.php");

$base_url = "/ECOMMERCE";

$query = "SELECT * FROM books ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!-- ✅ CSS WILL NOW WORK EVERYWHERE -->
<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/book_card.css">
<div class="book-container">

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <div class="book-card">
            <div class="book-image">
                <img src="<?php echo $base_url; ?>/admin/images/<?php echo $row['image']; ?>" alt="Book Image">

                <span class="price-tag">
                    Rs. <?php echo $row['price']; ?>
                </span>
            </div>

            <div class="book-info">
                <h3 class="book-title">
                    <?php echo $row['title']; ?>
                </h3>

                <div class="book-buttons">
                    <a href="<?php echo $base_url; ?>/pages/cart/add_to_cart.php?id=<?php echo $row['id']; ?>">
                        <button class="btn-cart">Add to Cart</button>
                    </a>

                    <a href="<?php echo $base_url; ?>/pages/products/product_detail.php?id=<?php echo $row['id']; ?>">
                        <button class="btn-details">View Details</button>
                    </a>
                </div>
            </div>
        </div>

    <?php } ?>

</div>