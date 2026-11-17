<?php
session_start();
$userName = $_SESSION['name'] ?? null;

if (isset($_SESSION['success'])) {
    echo "<script>alert('" . $_SESSION['success'] . "');</script>";
    unset($_SESSION['success']);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Store</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <link rel="stylesheet" href="/ECOMMERCE/assets/css/hero.css">

</head>

<body>
  <?php include("includes/header.php"); ?>
  <!-- ✅ HERO SECTION START -->
<section class="hero">
    <div class="hero-content">
        <h1>Discover Your Next Favorite Book</h1>
        <p>Explore thousands of books from all genres. Learn, grow, and enjoy reading.</p>

        <div class="hero-buttons">
            <a href="#books" class="btn-primary">Browse Books</a>
            <a href="/ECOMMERCE/pages/info/about.php" class="btn-secondary">Learn More</a>
        </div>
    </div>
</section>
<!-- ✅ HERO SECTION END -->

  <div id="books">
    <?php include("includes/book_card.php"); ?>
    <?php include("includes/footer.php"); ?>
</div>
</body>

</html>