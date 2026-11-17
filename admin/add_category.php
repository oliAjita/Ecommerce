<?php
include("../includes/db.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);

    // Basic validation
    if (empty($name)) {
        $errors['name'] = "Category name is required";
    }

    // Check if category already exists
    $checkQuery = "SELECT * FROM categories WHERE name='$name'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $errors['name'] = "Category already exists";
    }

    // If no errors → Insert
    if (empty($errors)) {

        $insertQuery = "INSERT INTO categories (name) VALUES ('$name')";

        if (mysqli_query($conn, $insertQuery)) {
            echo "<script>alert('Category Added Successfully'); window.location='dashboard.php?page=categories';</script>";
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="../../assets/css/admin/add_category.css">
</head>
<body>

<div class="container">
    <h2>Add New Category</h2>

    <form method="POST">

        <label>Category Name</label>
        <input type="text" name="name"
            value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>">

        <?php 
        if(isset($errors['name'])) {
            echo "<p style='color:red'>{$errors['name']}</p>";
        }
        ?>

        <button type="submit">Add Category</button>

    </form>
</div>

</body>
</html>
