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
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $imageName = $book['image']; // keep old image by default

    // If new image uploaded
    if (!empty($_FILES['photo']['name'])) {

        $filename = uniqid() . '_' . $_FILES['photo']['name'];

        move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            "images/" . $filename
        );

        $imageName = $filename;
    }

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
            <input type="text" name="title" value="<?php echo $book['title']; ?>" required>

            <label>Author</label>
            <input type="text" name="author" value="<?php echo $book['author']; ?>" required>

            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?php echo $book['price']; ?>" required>

            <label>Description</label>
            <textarea name="description" rows="4" required><?php echo $book['description']; ?></textarea>

            <label>Current Image</label><br>
            <img src="images/<?php echo $book['image']; ?>" class="preview-img"><br><br>

            <label>Change Image</label>
            <input type="file" name="photo">

            <button type="submit">Update Product</button>
        </form>
    </div>

</body>

</html>