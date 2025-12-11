<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Store</title>
  <link rel="stylesheet" href="/ECOMMERCE/assets/css/category_nav.css">
  <link rel="stylesheet" href="/ECOMMERCE/assets/css/index.css">
</head>

<body>

  <?php include("includes/header.php"); ?>

  <div class="main-layout">

    <!-- LEFT: CATEGORY NAVBAR -->
    <div class="left-categories">
      <?php include("includes/category_navbar.php"); ?>
    </div>

    <!-- RIGHT: BOOK CARDS -->
    <div class="right-books">
      <?php include("includes/book_card.php"); ?>
    </div>

  </div>

  <?php include("includes/footer.php"); ?>

</body>

</html>