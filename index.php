<!-- <?php
session_start();
?>
Welcome
<?php echo $_SESSION['name']
  ?>
<div>
  <a href="./user/logout.php">Logout</a>
</div> -->


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Store</title>
</head>

<body>
  <div>
    <?php include("includes/book_card.php"); ?>

  </div>
</body>

</html>