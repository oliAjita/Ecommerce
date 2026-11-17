<?php
include("../includes/db.php");

if (!isset($_GET['id'])) {
    die("Product ID missing");
}

$id = $_GET['id'];

// Fetch existing book data
$query = "SELECT * FROM books WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Book not found");
}

$book = mysqli_fetch_assoc($result);


// UPDATE LOGIC
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Trim inputs
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    // ===== BASIC VALIDATION =====

    if (empty($title)) {
        $errors['title'] = "Title is required";
    }

    if (empty($author)) {
        $errors['author'] = "Author is required";
    }

    if (empty($price)) {
        $errors['price'] = "Price is required";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errors['price'] = "Price must be a valid positive number";
    }

    if (empty($description)) {
        $errors['description'] = "Description is required";
    }

    $imageName = $book['image']; // keep old image by default

    // If new image uploaded
    if (!empty($_FILES['photo']['name'])) {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            $errors['photo'] = "Only JPG and PNG images are allowed";
        } else {
            $filename = uniqid() . '_' . $_FILES['photo']['name'];

            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                "images/" . $filename
            );

            $imageName = $filename;
        }
    }

    // ===== If no errors → Update =====
    if (empty($errors)) {

        $updateQuery = "UPDATE books SET 
                        title='$title',
                        author='$author',
                        price='$price',
                        description='$description',
                        image='$imageName'
                        WHERE id=$id";

        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>alert('Product Updated Successfully'); window.location='dashboard.php?page=products';</script>";
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
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/css/admin/edit_product.css">
</head>

<body>

    <div class="edit-container">
        <h2>Edit Book</h2>

        <form method="POST" enctype="multipart/form-data">

            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>">
            <?php if (isset($errors['title']))
                echo "<p style='color:red'>{$errors['title']}</p>"; ?>

            <label>Author</label>
            <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>">
            <?php if (isset($errors['author']))
                echo "<p style='color:red'>{$errors['author']}</p>"; ?>

            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($book['price']); ?>">
            <?php if (isset($errors['price']))
                echo "<p style='color:red'>{$errors['price']}</p>"; ?>

            <label>Description</label>
            <textarea name="description" rows="4"><?php echo htmlspecialchars($book['description']); ?></textarea>
            <?php if (isset($errors['description']))
                echo "<p style='color:red'>{$errors['description']}</p>"; ?>

            <label>Current Image</label><br>
            <img src="images/<?php echo $book['image']; ?>" class="preview-img"><br><br>

            <label>Change Image</label>
            <input type="file" name="photo">
            <?php if (isset($errors['photo']))
                echo "<p style='color:red'>{$errors['photo']}</p>"; ?>

            <button type="submit">Update Product</button>
        </form>
    </div>

</body>

</html>