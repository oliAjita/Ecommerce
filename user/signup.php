<?php
include("../includes/db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);


    // Use prepared statement for security
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        // Redirect after successful signup
        echo 'Successfully registered';
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/user/signup.css" />
</head>

<body>
    <div class="signup-container">
        <h2>signup to online book store</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="name" required><br>
            <input type="text" name="email" placeholder="email" required><br>
            <input type="password" name="password" placeholder="password" required><br>
            <button type="submit">signup</button>
        </form>
    </div>

</body>

</html>