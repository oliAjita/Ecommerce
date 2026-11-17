<?php
session_start();
include("../../includes/db.php");
include("../../includes/header.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<h2>Please log in to checkout.</h2>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if direct checkout product is provided
if (!isset($_GET['book_id'])) {
    echo "<h2>No product selected for direct checkout.</h2>";
    exit;
}

$book_id = intval($_GET['book_id']);
$quantity = isset($_GET['qty']) ? intval($_GET['qty']) : 1;

// Fetch book details
$book_query = "SELECT * FROM books WHERE id = $book_id";
$book_result = mysqli_query($conn, $book_query);

if (!$book_result || mysqli_num_rows($book_result) == 0) {
    echo "<h2>Book not found.</h2>";
    exit;
}

$book = mysqli_fetch_assoc($book_result);

// Ensure quantity does not exceed stock
if ($quantity > $book['stock']) {
    $quantity = $book['stock'];
}

// Prepare order array
$order = [
    $book_id => [
        'title' => $book['title'],
        'price' => $book['price'],
        'quantity' => $quantity
    ]
];

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
    <title>Direct Checkout - <?php echo htmlspecialchars($book['title']); ?></title>
    <link rel="stylesheet" href="/ECOMMERCE/assets/css/cart/checkout.css">
</head>
<body>

<div class="checkout-container">

    <h1 class="checkout-title">Direct Checkout</h1>

    <div class="checkout-layout">

        <!-- LEFT: CUSTOMER DETAILS -->
        <div class="checkout-form">

            <h2>Billing Details</h2>

            <form action="../order/place_order_direct.php" method="POST">

                <input type="hidden" name="direct_checkout" value="1">
                <input type="hidden" name="book_id[<?php echo $book_id; ?>]" value="<?php echo $quantity; ?>">

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
                <input type="text" name="pincode">

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
                foreach ($order as $id => $item) {
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
