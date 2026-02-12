<?php
session_start();
$base_url = "/ECOMMERCE";
include("../../includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: $base_url/user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$total = 0;

// Fetch cart items from database
$query = "SELECT c.*, b.title, b.author, b.price, b.image 
          FROM cart c
          JOIN books b ON c.book_id = b.id
          WHERE c.user_id = '$user_id'";

$result = mysqli_query($conn, $query);
?>

<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/cart/cart.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="cart-container">

    <div class="cart-header">
        <h1>Your Cart</h1>
        <div class="small">
            <?php echo mysqli_num_rows($result); ?> items
        </div>
    </div>

    <?php if (mysqli_num_rows($result) == 0) { ?>
        <div class="cart-empty">
            <h2>Your cart is empty!</h2>
        </div>
    <?php } else { ?>

        <div class="cart-layout">

            <div class="cart-items">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($result)) {
                            $subtotal = $row['price'] * $row['quantity'];
                            $total += $subtotal;
                            ?>

                            <tr>
                                <td>
                                    <div class="cart-product">
                                        <img src="<?php echo $base_url; ?>/admin/images/<?php echo $row['image']; ?>">
                                        <div class="prod-info">
                                            <h4>
                                                <?php echo $row['title']; ?>
                                            </h4>
                                            <p>
                                                <?php echo $row['author']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?php echo $row['quantity']; ?>
                                </td>

                                <td>Rs
                                    <?php echo $row['price']; ?>
                                </td>

                                <td>Rs
                                    <?php echo $subtotal; ?>
                                </td>

                                <td>
                                    <form action="remove_item.php" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $row['book_id']; ?>">
                                        <button class="btn-remove">Remove</button>
                                    </form>
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>
                </table>
            </div>

            <aside class="cart-summary">
                <h3>Order Summary</h3>

                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>Rs
                        <?php echo $total; ?>
                    </span>
                </div>

                <div class="summary-row total">
                    <span>Total:</span>
                    <span>Rs
                        <?php echo $total; ?>
                    </span>
                </div>

                <a href="checkout.php" class="btn-checkout">← Proceed to Checkout</a>
                <a href="<?php echo $base_url; ?>/index.php" class="continue-link">← Continue Shopping</a>
            </aside>

        </div>

    <?php } ?>
</div>

<?php
if (isset($_SESSION['success'])) {
    echo "<script>toastr.success('" . $_SESSION['success'] . "');</script>";
    unset($_SESSION['success']);
}
?>