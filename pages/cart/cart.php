<?php
session_start();
$base_url = "/ECOMMERCE";
include("../../includes/db.php");

$cart = $_SESSION['cart'] ?? [];
$total = 0;
if (isset($_POST['checkout'])) {
    header("Location: checkout.php");
    exit;
}
?>

<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/cart/cart.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<div class="cart-container">

    <div class="cart-header">
        <h1>Your Cart</h1>
        <div class="small"><?php echo count($cart); ?> items</div>
    </div>

    <?php
    if (empty($cart)) {
        echo "<div class='cart-empty'><h2>Your cart is empty!</h2></div>";
        exit;
    }
    ?>

    <div class="cart-layout">

        <!-- CART ITEMS -->
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

                    <?php
                    foreach ($cart as $book_id => $qty) {
                        $query = "SELECT * FROM books WHERE id=$book_id";
                        $result = mysqli_query($conn, $query);
                        $book = mysqli_fetch_assoc($result);

                        $subtotal = $book['price'] * $qty;
                        $total += $subtotal;
                        ?>

                        <tr>
                            <td>
                                <div class="cart-product">
                                    <img src="<?php echo $base_url; ?>/admin/images/<?php echo $book['image']; ?>" alt="">
                                    <div class="prod-info">
                                        <h4><?php echo $book['title']; ?></h4>
                                        <p><?php echo $book['author']; ?></p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="qty-controls">
                                    <form action="update_qty.php" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $book_id; ?>">
                                        <button name="action" value="minus">-</button>
                                        <input type="text" value="<?php echo $qty; ?>" disabled>
                                        <button name="action" value="plus">+</button>
                                    </form>
                                </div>
                            </td>

                            <td class="cart-price">Rs <?php echo $book['price']; ?></td>

                            <td class="cart-subtotal">Rs <?php echo $subtotal; ?></td>

                            <td>
                                <form action="remove_item.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $book_id; ?>">
                                    <button class="btn-remove">Remove</button>
                                </form>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>

        <!-- CART SUMMARY -->
        <aside class="cart-summary">
            <h3>Order Summary</h3>

            <div class="summary-row">
                <span>Subtotal:</span>
                <span>Rs <?php echo $total; ?></span>
            </div>

            <div class="summary-row total">
                <span>Total:</span>
                <span>Rs <?php echo $total; ?></span>
            </div>

            <!-- <button class="btn-checkout" name="checkout">Proceed to Checkout</button> -->
            <a href="checkout.php" class="btn-checkout">← Proceed to Checkout</a>


            <a href="<?php echo $base_url; ?>/index.php" class="continue-link">← Continue Shopping</a>
        </aside>

    </div>
</div>
<?php
if (isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    echo "<script>
            $(document).ready(function() {
                toastr.success('$msg');
            });
          </script>";
    unset($_SESSION['success']);
}
?>