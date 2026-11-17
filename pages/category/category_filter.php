<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../../includes/header.php"); // This includes db.php too

$base_url = "/ECOMMERCE";
$isLoggedIn = isset($_SESSION['user_id']);

// --- Validate category ID ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid category.");
}

$category_id = intval($_GET['id']);

// --- Fetch category details ---
$cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$cat_stmt->bind_param("i", $category_id);
$cat_stmt->execute();
$cat_result = $cat_stmt->get_result();

if ($cat_result->num_rows === 0) {
    die("Category not found.");
}

$category = $cat_result->fetch_assoc();
$cat_stmt->close();

// --- Fetch books in this category ---
$book_stmt = $conn->prepare("SELECT * FROM books WHERE category_id = ? ORDER BY id DESC");
$book_stmt->bind_param("i", $category_id);
$book_stmt->execute();
$book_result = $book_stmt->get_result();
?>

<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/book_card.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/category/category_filter.css">


<div class="header">
    <h1>Showing results for <?php echo htmlspecialchars($category['name']); ?> Books</h1>
    <p>Browse all books in this category.</p>
</div>

<div class="book-container">
    <?php if ($book_result->num_rows === 0) : ?>
        <p>No books found in this category.</p>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($book_result)) {
            $in_stock = $row['stock'] > 0; ?>

            <!-- ✅ Entire Card Clickable -->
            <div class="book-card"
                onclick="window.location='<?php echo $base_url; ?>/pages/products/product_detail.php?id=<?php echo $row['id']; ?>'">

                <div class="book-image">
                    <img src="<?php echo $base_url; ?>/admin/images/<?php echo $row['image']; ?>" alt="Book Image">

                    <span class="price-tag">Rs. <?php echo $row['price']; ?></span>

                    <!-- Out of Stock Tag -->
                    <?php if (!$in_stock) { ?>
                        <span class="out-of-stock-tag">Out of Stock</span>
                    <?php } ?>
                </div>

                <div class="book-info">
                    <h3 class="book-title"><?php echo htmlspecialchars($row['title']); ?></h3>

                    <div class="book-buttons">
                        <?php if ($isLoggedIn) { ?>
                            <a href="<?php echo $base_url; ?>/pages/cart/add_to_cart.php?id=<?php echo $row['id']; ?>"
                                onclick="event.stopPropagation();">
                                <button class="btn-cart" <?php echo $in_stock ? "" : "disabled style='opacity:0.5; cursor:not-allowed;'"; ?>>
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
        <?php } ?>
    <?php endif; ?>
</div>

<?php
$book_stmt->close();
include("../../includes/footer.php");
?>
