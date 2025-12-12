<?php
session_start();
include("../../includes/db.php");

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<h2>Your cart is empty!</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="/ECOMMERCE/assets/css/cart/checkout.css">
</head>

<body>

    <div class="checkout-container">

        <h1 class="checkout-title">Checkout</h1>

        <div class="checkout-layout">

            <!-- LEFT SIDE: CUSTOMER DETAILS -->
            <div class="checkout-form">

                <h2>Billing Details</h2>

                <form action="place_order.php" method="POST">

                    <label>Full Name</label>
                    <input type="text" name="fullname" required>

                    <label>Email</label>
                    <input type="email" name="email" required>

                    <label>Phone Number</label>
                    <input type="text" name="phone" required>

                    <label>Address</label>
                    <textarea name="address" rows="3" required></textarea>

                    <label>City</label>
                    <input type="text" name="city" required>

                    <label>Postal Code</label>
                    <input type="text" name="pincode" required>


                    <!-- PAYMENT METHOD -->
                    <div class="payment-box">
                        <h3>Payment Method</h3>

                        <label class="radio-option">
                            <input type="radio" name="payment" value="COD" checked>
                            Cash on Delivery (COD)
                        </label>

                    </div>

                    <button type="submit" class="btn-place-order">Place Order</button>

                </form>

            </div>

            <!-- RIGHT SIDE: ORDER SUMMARY -->
            <div class="checkout-summary">
                <h2>Order Summary</h2>

                <div class="summary-items">

                    <?php
                    $total = 0;

                    foreach ($cart as $book_id => $qty) {
                        $query = "SELECT * FROM books WHERE id=$book_id";
                        $result = mysqli_query($conn, $query);
                        $book = mysqli_fetch_assoc($result);

                        $subtotal = $book['price'] * $qty;
                        $total += $subtotal;

                        echo "
                        <div class='summary-item'>
                            <span>{$book['title']} × $qty</span>
                            <span>Rs $subtotal</span>
                        </div>
                    ";
                    }
                    ?>

                </div>

                <div class="summary-total">
                    <strong>Total:</strong>
                    <strong>Rs <?php echo $total; ?></strong>
                </div>
            </div>

        </div>

    </div>

</body>

</html>