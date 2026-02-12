<?php
session_start();
include("../includes/header.php");
include("../includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="/ECOMMERCE/assets/css/user/profile.css">
</head>

<body>

    <div class="profile-container">
        <h2>My Profile</h2>

        <div class="profile-card">
            <div class="profile-info">
                <p><strong>Name:</strong>
                    <?php echo htmlspecialchars($user['name']); ?>
                </p>
                <p><strong>Email:</strong>
                    <?php echo htmlspecialchars($user['email']); ?>
                </p>
                <p><strong>Phone:</strong>
                    <?php echo htmlspecialchars($user['phone']); ?>
                </p>
                <p><strong>Address:</strong>
                    <?php echo htmlspecialchars($user['address']); ?>
                </p>
            </div>

            <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
        </div>
    </div>

</body>

</html>


<?php include("../includes/footer.php"); ?>