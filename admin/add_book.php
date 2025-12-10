<?php
include("../includes/db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    //image upload handling
    if ($_FILES['photo']['error'] == 0) {
        if ($_FILES['photo']['size'] < 500000) {
            $filetype = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg'];
            if (in_array($_FILES['photo']['type'], $filetype)) {
                $filename = uniqid() . '_' . $_FILES['photo']['name'];
                if (move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $filename)) {
                    $query = "INSERT INTO books(title,author,price,description,image,created_at) VALUES('$title','$author','$price','$description','$filename',NOW())";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        echo "<script>alert('Book added successfully!');</script>";
                    } else {
                        echo "<script>alert('Error adding book: " . mysqli_error($conn) . "');</script>";
                    }
                    // echo 'Upload Success';
                } else {
                    echo 'Upload failed';
                }
            } else {
                echo 'File type must be png/jpg/jepg/gif';
            }
        } else {
            echo 'File size must be below 500kb';
        }
    } else {
        echo 'Fileupload Error';
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link rel="stylesheet" href="../assets/css/admin/add_book.css" />
</head>

<body>
    <form action="add_book.php" method="POST" enctype="multipart/form-data">
        <div class="add-book">
            <h1>Add Book</h1>
            <label>Book Title</label>
            <input type="text" name="title" required>
            <label>Author</label>
            <input type="text" name="author" required>
            <label>Price</label>
            <input type="number" step="0.01" name="price" required>
            <label>Description</label>
            <textarea name="description" rows="4" required></textarea>

            <!-- image -->
            <label>Book Image</label>
            <input type="file" name="photo" required>

            <button type="submit">Add Book</button>
        </div>
    </form>

</body>

</html>