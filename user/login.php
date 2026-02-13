<?php
include("../includes/db.php");

$emailError = $passwordError = $roleError = $loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Email validation
    if (empty($email)) {
        $emailError = "Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format!";
    }

    // Password validation
    if (empty($password)) {
        $passwordError = "Password is required!";
    }

    // Role validation
    if (empty($role)) {
        $roleError = "Please select a role!";
    }

    // Only proceed if no validation errors
    if (empty($emailError) && empty($passwordError) && empty($roleError)) {

        $passwordMd5 = md5($password); // keep your current MD5 logic

        $query = "SELECT * FROM users WHERE email='$email' AND password='$passwordMd5'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            session_start();
            if ($user['role'] == 'admin') {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_email'] = $email;
                $_SESSION['admin_role'] = $user['role'];
                header("Location: ../admin/dashboard.php");
                exit();
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $user['role'];
                header("Location: ../index.php");
                exit();
            }
        } else {
            $loginError = "Invalid email or password!";
        }
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
            <input type="text" name="email" placeholder="Enter your email"
                value="<?php echo htmlspecialchars($email ?? ''); ?>"><br>
            <span class="error-msg"><?php echo $emailError; ?></span>

            <input type="password" name="password" placeholder="Enter password"><br>
            <span class="error-msg"><?php echo $passwordError; ?></span>
            <br />
            <br />

            <label for="role">Select Role:</label>
            <select id="role" name="role">
                <option value="">--Select Role--</option>
                <option value="user" <?php if (($role ?? '') == 'user')
                    echo 'selected'; ?>>User</option>
                <option value="admin" <?php if (($role ?? '') == 'admin')
                    echo 'selected'; ?>>Admin</option>
            </select>
            <br />
            <span class="error-msg"><?php echo $roleError; ?></span>

            <br><br>
            <button type="submit">Login</button>

            <span class="error-msg"><?php echo $loginError; ?></span>

            <p class="register" id="signup-text">Don't have an account? <a href="/ECOMMERCE/user/signup.php">Create
                    one</a></p>
        </form>

    </div>
    <script>
        const roleDropdown = document.getElementById('role');
        const signupText = document.getElementById('signup-text');

        roleDropdown.addEventListener('change', () => {
            if (roleDropdown.value === 'admin') {
                signupText.style.display = 'none';
            } else {
                signupText.style.display = 'block';
            }
        });
    </script>
</body>

</html>