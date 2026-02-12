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
        <h2>SIGNUP TO ONLINE BOOK STORE</h2>
        <form method="POST" action="">

            <div>
                <label for="name" class="name">Full Name</label>

                <input type="text" name="name" placeholder="Ramesh Pathak" required><br>
            </div>

            <div>
                <label for="email" class="email">Email</label>
                <input type="text" name="email" placeholder="rameshpathak@gmail.com" required><br>

            </div>

            <div>
                <label for="Password" class="Password">Password</label>
                <input type="password" name="password" placeholder="********" required><br>


            </div>

            <div>
                <button type="submit">Signup</button>
            </div>


        </form>
    </div>

</body>

</html>