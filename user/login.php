<?php
include("../includes/db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        session_start();

        if ($user['role'] == 'admin') {
            // Separate session for admin
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['name'];
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_role'] = $user['role'];
            header("Location: ../admin/dashboard.php");
            exit();
        } else {
            // Separate session for normal user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];
            header("Location: ../index.php");
            exit();
        }

    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BookStore</title>
    <link rel="stylesheet" href="../assets/css/user/login.css" />
</head>

<body>
    <div class="login-container">
        <h2>Login to Online Book Store</h2>
        <form method="POST" action="">
            <div class="error-msg">
                <?php echo $error ?? '' ?>
            </div>
            <input type="text" name="email" placeholder="Enter your email" required><br>
            <input type="password" name="password" placeholder="Enter password" required><br>
            <button type="submit">Login</button>
            <p class="register">Don't have an account? <a href="/ECOMMERCE/user/signup.php">Create one</a></p>
        </form>
    </div>
</body>

</html>