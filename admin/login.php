<?php
session_start();
include("../includes/db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $hashed_password = md5($password); // for demo — use password_hash() in production

        $sql = "SELECT * FROM admins WHERE email='$email' AND password='$hashed_password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | Online Book Store</title>
    <link rel="stylesheet" href="../assets/css/admin/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>