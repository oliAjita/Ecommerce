<?php
session_start();
include("../includes/header.php");
include("../includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables
$name = $phone = $address = $password = $confirm_password = '';
$errors = [];
$success = '';

// --- Fetch current user data ---
$stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    die("User not found.");
}
$user = $result->fetch_assoc();
$stmt->close();

// Pre-fill form
$name = $user['name'];
$phone = $user['phone'];
$address = $user['address'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST values and trim
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // --- VALIDATION ---
    if (empty($name)) $errors['name'] = "Name is required.";
    if (empty($phone)) $errors['phone'] = "Phone is required.";
    if (empty($address)) $errors['address'] = "Address is required.";

    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors['password'] = "Password must be at least 6 characters.";
        } elseif ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }
    }

    // --- IF NO ERRORS, UPDATE USER ---
    if (empty($errors)) {
        if (!empty($password)) {
            // Hash new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, address=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $phone, $address, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
            $stmt->bind_param("sssi", $name, $phone, $address, $user_id);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
        } else {
            $errors['db'] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="/ECOMMERCE/assets/css/user/profile.css">
    <style>
        .profile-container { max-width:600px; margin:40px auto; padding:20px; }
        .profile-card { background:#fff; padding:20px; border-radius:10px; box-shadow:0px 0px 8px rgba(0,0,0,0.1);}
        .form-group { margin-bottom:15px; }
        .form-group label { display:block; margin-bottom:5px; }
        .form-group input, .form-group textarea { width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;}
        .error { color:red; font-size:14px; margin-top:3px; }
        .success { color:green; font-weight:bold; margin-bottom:15px; }
        button { padding:10px 20px; background:#4CAF50; color:#fff; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#45a049; }
    </style>
</head>

<body>

<div class="profile-container">
    <h2>Edit Profile</h2>

    <div class="profile-card">
        <?php if (!empty($success)) : ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)) : ?>
            <div class="error">
                <?php foreach ($errors as $err) echo $err . "<br>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address"><?php echo htmlspecialchars($address); ?></textarea>
            </div>

            <div class="form-group">
                <label>New Password (leave blank to keep current)</label>
                <input type="password" name="password">
            </div>

            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password">
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</div>

</body>
</html>

<?php include("../includes/footer.php"); ?>
