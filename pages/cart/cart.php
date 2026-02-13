<?php
session_start();
$base_url = "/ECOMMERCE";
include("../../includes/db.php");
include("../../includes/header.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: $base_url/user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];/////////////
if (isset($_POST['confirm_change'])) {

    foreach ($_POST['quantity'] as $book_id => $new_qty) {

        $book_id = intval($book_id);
        $new_qty = intval($new_qty);

        // Get current cart quantity
        $cart_query = mysqli_query($conn, "SELECT quantity FROM cart 
                                           WHERE user_id='$user_id' AND book_id='$book_id'");
        $cart_data = mysqli_fetch_assoc($cart_query);
        $current_qty = $cart_data['quantity'];

        // Difference
        $difference = $new_qty - $current_qty;

        if ($difference > 0) {
            // Increase cart, decrease stock
            mysqli_query($conn, "UPDATE books 
                                 SET stock = stock - $difference 
                                 WHERE id='$book_id'");
        } elseif ($difference < 0) {
            // Decrease cart, increase stock
            $abs_diff = abs($difference);
            mysqli_query($conn, "UPDATE books 
                                 SET stock = stock + $abs_diff 
                                 WHERE id='$book_id'");
        }

        // Update cart quantity
        mysqli_query($conn, "UPDATE cart 
                             SET quantity='$new_qty' 
                             WHERE user_id='$user_id' AND book_id='$book_id'");
    }

    $_SESSION['success'] = "Cart updated successfully!";
    header("Location: cart.php");
    exit;
}
////////////////
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
        <div class="small"><?php echo mysqli_num_rows($result); ?> items</div>
    </div>

    <?php if (mysqli_num_rows($result) == 0) { ?>
        <div class="cart-empty">
            <h2>Your cart is empty!</h2>
        </div>
    <?php } else { ?>

        <div class="cart-layout">

            <div class="cart-items">
                <form method="POST">
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
                                                <h4><?php echo $row['title']; ?></h4>
                                                <p><?php echo $row['author']; ?></p>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="qty-controls">
                                            <button type="button" class="minus-btn">-</button>
                                            <input type="number" name="quantity[<?php echo $row['book_id']; ?>]"
                                                value="<?php echo $row['quantity']; ?>" min="1">
                                            <button type="button" class="plus-btn">+</button>
                                        </div>
                                    </td>


                                    <td>Rs <?php echo $row['price']; ?></td>

                                    <td>Rs <?php echo $subtotal; ?></td>

                                    <td>
                                        <button type="button" class="btn-remove"
                                            data-book-id="<?php echo $row['book_id']; ?>">Remove</button>

                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>
                    <button type="submit" name="confirm_change" class="btn-confirm">
                        Confirm Change
                    </button>
                </form>

            </div>

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
include("../../includes/footer.php");


?>
<script>
    document.querySelectorAll(".plus-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let input = this.parentElement.querySelector("input");
            input.value = parseInt(input.value) + 1;
        });
    });

    document.querySelectorAll(".minus-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let input = this.parentElement.querySelector("input");
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    document.querySelectorAll(".btn-remove").forEach(btn => {
    btn.addEventListener("click", function() {
        let bookId = this.getAttribute("data-book-id");
        if (confirm("Are you sure you want to remove this item?")) {
            window.location.href = "remove_item.php?id=" + bookId;
        }
    });
});

</script>