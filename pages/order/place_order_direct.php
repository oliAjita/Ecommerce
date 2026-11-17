<?php
session_start();
include("../../includes/db.php");
include("../../includes/header.php");



// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<h2>Please log in to place an order.</h2>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if this is a direct checkout
    if (!isset($_POST['direct_checkout']) || !isset($_POST['book_id']) || !is_array($_POST['book_id'])) {
        echo "<h2>No product selected for direct checkout.</h2>";
        exit;
    }

    // Collect form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'] ?? '';
    $payment_method = $_POST['payment'];

    // Store ordered items for invoice
    $ordered_items = [];
    $grand_total = 0;

    // Loop through all products in direct checkout (usually one)
    foreach ($_POST['book_id'] as $book_id => $quantity) {

        $book_id = intval($book_id);
        $quantity = intval($quantity);

        // Fetch book info and check stock
        $book_query = "SELECT title, price, stock FROM books WHERE id = $book_id";
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

        $total_amount = $book['price'] * $quantity;
        $book_name = $book['title'];
        $grand_total += $total_amount;

        // Store item for invoice
        $ordered_items[] = [
            'title' => $book_name,
            'price' => $book['price'],
            'quantity' => $quantity,
            'subtotal' => $total_amount
        ];

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id,book_id, fullname, email, phone, address, city, pincode, payment_method, total_amount, book_name, total_quantity, order_date) VALUES (?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "iisssssssdsi",
            $user_id,
            $book_id,
            $fullname,
            $email,
            $phone,
            $address,
            $city,
            $pincode,
            $payment_method,
            $total_amount,
            $book_name,
            $quantity
        );

        $stmt->execute();
        $stmt->close();

        // Reduce stock
        mysqli_query($conn, "UPDATE books SET stock = stock - $quantity WHERE id = $book_id");
    }

    // ================= DISPLAY THANK YOU & INVOICE =================
    echo '
    <div style="max-width:700px; margin:40px auto; font-family:Arial,sans-serif; border:1px solid #ccc; padding:30px; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.1);">
        <h1 style="color:#27ae60; text-align:center;">Thank You for Your Purchase!</h1>
        <p style="text-align:center; font-size:16px; color:#555;">Dear <strong>'.htmlspecialchars($fullname).'</strong>, we appreciate your order. Your books will be delivered to the following address:</p>
        <p style="text-align:center; font-size:16px; color:#555;">
            '.htmlspecialchars($address).', '.htmlspecialchars($city).' - '.htmlspecialchars($pincode).'<br>
            Phone: '.htmlspecialchars($phone).'<br>
            Email: '.htmlspecialchars($email).'
        </p>

        <h2 style="margin-top:30px; border-bottom:1px solid #ccc; padding-bottom:5px;">Order Invoice</h2>
        <table style="width:100%; border-collapse:collapse; margin-top:15px;">
            <thead>
                <tr style="background-color:#f7f7f7; text-align:left;">
                    <th style="padding:10px; border:1px solid #ddd;">Book</th>
                    <th style="padding:10px; border:1px solid #ddd;">Quantity</th>
                    <th style="padding:10px; border:1px solid #ddd;">Price</th>
                    <th style="padding:10px; border:1px solid #ddd;">Subtotal</th>
                </tr>
            </thead>
            <tbody>';
                foreach ($ordered_items as $item) {
                    echo '<tr>
                        <td style="padding:10px; border:1px solid #ddd;">'.htmlspecialchars($item['title']).'</td>
                        <td style="padding:10px; border:1px solid #ddd;">'.$item['quantity'].'</td>
                        <td style="padding:10px; border:1px solid #ddd;">Rs '.$item['price'].'</td>
                        <td style="padding:10px; border:1px solid #ddd;">Rs '.$item['subtotal'].'</td>
                    </tr>';
                }
    echo '</tbody>
        </table>

        <h3 style="text-align:right; margin-top:15px;">Grand Total: Rs '.$grand_total.'</h3>

        <p style="text-align:center; margin-top:30px; font-size:16px; color:#555;">
            Thank you for choosing us! We hope you enjoy your books.
        </p>
    </div>
    ';
    exit;
}
include("../../includes/footer.php")
?>

