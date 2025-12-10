<?php
include("../includes/db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);


    $query = "SELECT* FROM users WHERE email='$email' AND password ='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        //fetch user details from the database
        $user = mysqli_fetch_assoc($result);

        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role']; // first change for role based authentication

        if ($user['role'] == 'admin') {
            header("location:../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit();

    } else {
        $error = "Invalid username or password!";
        echo $error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/user/login.css" />
</head>

<body>
    <div class="login-container">
        <h2>Login to Online Book Store</h2>
        <form method="POST" action="">
            <input type="text" name="email" placeholder="Enter email" required><br>
            <input type="password" name="password" placeholder="Enter password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>