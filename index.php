<?php
session_start();
?>
Welcome
<?php echo $_SESSION['name']
  ?>
<div>
  <a href="./user/logout.php">Logout</a>
</div>