<?php
session_start();
include("../../includes/db.php");
$base_url = "/ECOMMERCE";
$isLoggedIn = isset($_SESSION['user_id']);

if (!isset($_GET['id'])) {
    die("Product ID is required");
}

$id = $_GET['id'];


//fetch book data from database
$query = "SELECT * FROM books where id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Book not found");
}

$row = mysqli_fetch_assoc($result);
$in_stock = $row['stock'] > 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title><?php echo $row['title']; ?>Book Details</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/product_details.css" />
     <style>
        /* ✅ Out of Stock Tag Style */
        .out-of-stock-label {
            display: inline-block;
            background-color: red;
            color: white;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include("../../includes/header.php"); ?>
    <div class="details-container">
        

        <!-- ✅ LEFT: BOOK IMAGE -->
        <div class="details-image">
            <img src="<?php echo $base_url ?>/admin/images/<?php echo $row['image']; ?>" alt="Book Image">
        </div>

        <!-- ✅ RIGHT: BOOK INFO -->
        <div class="details-info">
            <h1 class="book-title"><?php echo $row['title']; ?></h1>

            <p class="book-author">
                by <span><?php echo $row['author']; ?></span>
            </p>

            <p class="book-price">Rs<?php echo $row['price']; ?></p>

            <p class="book-description">
                <?php echo $row['description']; ?>
            </p>
            <?php if (!$in_stock) { ?>
                <div class="out-of-stock-label">Out of Stock</div>
            <?php } ?>

            <div class="details-buttons">
                <?php if ($isLoggedIn) { ?>
                <!-- Show Add to Cart ONLY if logged in -->
                        <a href="<?php echo $base_url; ?>/pages/cart/add_to_cart.php?id=<?php echo $row['id']; ?>"
                                                <?php echo $in_stock ? "" : "onclick='return false;'"; ?>>
                                                <button class="btn-cart" <?php echo $in_stock ? "" : "disabled style='opacity:0.5; cursor:not-allowed;'"; ?>>
                            Add to Cart
                        </button>                        </a>
                    <?php } ?>
                <!-- <button class="btn-cart">Add to Cart</button> -->
                <!-- <button class="btn-back">Back to Shop</button> -->
                 <a href="<?php echo $base_url; ?>/pages/cart/single_checkout.php?book_id=<?php echo $row['id']; ?>"
                                                                 <?php echo $in_stock ? "" : "onclick='return false;'"; ?>>

                            <button class="btn-back" <?php echo $in_stock ? "" : "disabled style='opacity:0.5; cursor:not-allowed;'"; ?>    >Buy Now</button>
                        </a>
            </div>
        </div>

    </div>

    <?php include("../../includes/footer.php"); ?>
</body>

</html>