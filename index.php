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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


</head>


<body>
  <?php include("includes/header.php"); ?>

  <div>
    <?php include("includes/book_card.php"); ?>
    <?php include("includes/footer.php"); ?>





  </div>


</body>

</html>