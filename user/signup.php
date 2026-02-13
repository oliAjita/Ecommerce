<?php
include("../includes/db.php");

// Initialize variables and error messages
$name = $email = $password = $confirm_password = $phone = $address = $city = "";
$name_err = $email_err = $password_err = $confirm_password_err = $phone_err = $address_err = $city_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- NAME VALIDATION ---
    if (empty(trim($_POST['name']))) {
        $name_err = "Full Name is required";
    } else {
        $name = trim($_POST['name']);
    }

    // --- EMAIL VALIDATION ---
    if (empty(trim($_POST['email']))) {
        $email_err = "Email is required";
    } else {
        $email = trim($_POST['email']);
        // Nepali email regex standard
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format";
        }
    }

    // --- PASSWORD VALIDATION ---
    if (empty($_POST['password'])) {
        $password_err = "Password is required";
    } else {
        $password = $_POST['password'];
        if (strlen($password) < 6) {
            $password_err = "Password must be at least 6 characters";
        }
    }

    // --- CONFIRM PASSWORD ---
    if (empty($_POST['confirm_password'])) {
        $confirm_password_err = "Confirm your password";
    } else {
        $confirm_password = $_POST['confirm_password'];
        if ($password !== $confirm_password) {
            $confirm_password_err = "Passwords do not match";
        }
    }

    // --- PHONE VALIDATION ---
    if (empty(trim($_POST['phone']))) {
        $phone_err = "Phone number is required";
    } else {
        $phone = trim($_POST['phone']);
        // Nepali phone number format: starts with 9, then 9 digits, or +977
        if (!preg_match("/^(?:\+977)?9\d{9}$/", $phone)) {
            $phone_err = "Invalid phone number";
        }
    }

    // --- ADDRESS VALIDATION ---
    if (empty(trim($_POST['address']))) {
        $address_err = "Address is required";
    } else {
        $address = trim($_POST['address']);
    }

    // --- CITY VALIDATION ---
    if (empty(trim($_POST['city']))) {
        $city_err = "City is required";
    } else {
        $city = trim($_POST['city']);
    }

    // If no errors, insert into database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($phone_err) && empty($address_err) && empty($city_err)) {
        $hashed_password = md5($password);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address, city) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssssss", $name, $email, $hashed_password, $phone, $address, $city);

        if ($stmt->execute()) {
            echo 'Successfully registered';
            header("Location: ../index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Online Book Store</title>
    <link rel="stylesheet" href="../assets/css/user/signup.css" />
    <style>
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 2px;
        }

        input,
        textarea {
            display: block;
            margin-bottom: 10px;
            width: 100%;
            padding: 8px;
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <h2>SIGNUP TO ONLINE BOOK STORE</h2>
        <form method="POST" action="">

            <label>Full Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Ramesh Pathak">
            <div class="error"><?php echo $name_err; ?></div>

            <label>Email</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"
                placeholder="rameshpathak@gmail.com">
            <div class="error"><?php echo $email_err; ?></div>

            <label>Password</label>
            <input type="password" name="password" placeholder="********">
            <div class="error"><?php echo $password_err; ?></div>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="********">
            <div class="error"><?php echo $confirm_password_err; ?></div>

            <label>Phone Number</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="98XXXXXXXX">
            <div class="error"><?php echo $phone_err; ?></div>

            <label>Address</label>
            <textarea name="address" rows="3"
                placeholder="Kathmandu, Nepal"><?php echo htmlspecialchars($address); ?></textarea>
            <div class="error"><?php echo $address_err; ?></div>

            <label>City</label>
            <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" placeholder="Kathmandu">
            <div class="error"><?php echo $city_err; ?></div>

            <button type="submit">Signup</button>
        </form>
        <span class="login-btn">Already have an account ? <a href="login.php">Login</a></span>
    </div>
</body>

</html>