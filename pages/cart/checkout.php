<?php
session_start();
include("../../includes/db.php");
include("../../includes/header.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<h2>Please log in to view your cart.</h2>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Retrieve cart items from database
$cart_query = "SELECT c.book_id, c.quantity, b.title, b.price 
               FROM cart c
               JOIN books b ON c.book_id = b.id
               WHERE c.user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);

if (!$cart_result || mysqli_num_rows($cart_result) == 0) {
    echo "<h2>Your cart is empty!</h2>";
    exit;
}

// Store cart items in an array
$cart = [];
while ($row = mysqli_fetch_assoc($cart_result)) {
    $cart[$row['book_id']] = [
        'title' => $row['title'],
        'price' => $row['price'],
        'quantity' => $row['quantity']
    ];
}

// Fetch user details
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);

if ($user_result && mysqli_num_rows($user_result) == 1) {
    $user = mysqli_fetch_assoc($user_result);
} else {
    $user = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'city' => '',
        'pincode' => ''
    ];
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

            <form action="../order/place_order.php" method="POST">

                <label>Full Name</label>
                <input type="text" name="fullname" required value="<?php echo htmlspecialchars($user['name']); ?>">

                <label>Email</label>
                <input type="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">

                <label>Phone Number</label>
                <input type="text" name="phone" required value="<?php echo htmlspecialchars($user['phone']); ?>">

                <label>Address</label>
                <textarea name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>

                <label>City</label>
                <input type="text" name="city" required value="<?php echo htmlspecialchars($user['city']); ?>">

                <label>Postal Code</label>
                <input type="text" name="pincode" >

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

                foreach ($cart as $book_id => $item) {
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;

                    echo "
                    <div class='summary-item'>
                        <span>{$item['title']} × {$item['quantity']}</span>
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
<?php include("../../includes/footer.php"); ?>
