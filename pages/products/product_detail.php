<?php
include("../../includes/db.php");
$base_url = "/ECOMMERCE";

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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $row['title']; ?>Book Details</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/product_details.css" />
</head>

<body>

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

            <div class="details-buttons">
                <button class="btn-cart">Add to Cart</button>
                <button class="btn-back">Back to Shop</button>
            </div>
        </div>

    </div>

</body>

</html>