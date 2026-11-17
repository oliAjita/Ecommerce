<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../includes/db.php");

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized");
}

$errors = [];
$success = "";
// Handle add book

if ($page == 'add_book' && $_SERVER["REQUEST_METHOD"] == "POST") {
    // --- SANITIZE INPUT ---
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $category_id = trim($_POST['category_id'] ?? '');

    // --- VALIDATE INPUT ---
    if (empty($title))
        $errors['title'] = "Book title is required.";
    if (empty($author))
        $errors['author'] = "Author is required.";
    if (empty($category_id) || !is_numeric($category_id))
        $errors['category'] = "Select a valid category.";

    if (empty($price)) {
        $errors['price'] = "Price is required.";
    } elseif (!is_numeric($price) || $price < 0) {
        $errors['price'] = "Price must be a positive number.";
    }

    if (empty($quantity)) {
        $errors['quantity'] = "Quantity is required.";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $errors['quantity'] = "Quantity must be a positive number.";
    }

    if (empty($description))
        $errors['description'] = "Description is required.";

    // --- PROCESS IF NO ERRORS ---
    if (empty($errors)) {
        // Keep your original image upload logic
        if ($_FILES['photo']['error'] == 0) {
            if ($_FILES['photo']['size'] < 500000) {
                $filetype = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg'];
                if (in_array($_FILES['photo']['type'], $filetype)) {
                    $filename = uniqid() . '_' . $_FILES['photo']['name'];
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $filename)) {

                        // --- INSERT INTO DB USING PREPARED STATEMENT ---
                        $stmt = $conn->prepare("INSERT INTO books(title, author, price, description, image, category_id, stock, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
                        if (!$stmt) {
                            die("Prepare failed: " . $conn->error);
                        }

                        $stmt->bind_param(
                            "ssdssii",
                            $title,
                            $author,
                            $price,
                            $description,
                            $filename,
                            $category_id,
                            $quantity
                        );

                        if ($stmt->execute()) {
                            $success = "Book added successfully!";
                            // Clear form values
                            $title = $author = $price = $description = $quantity = '';
                            $category_id = '';
                        } else {
                            $errors['db'] = "Database error: " . $stmt->error;
                        }

                        $stmt->close();
                    } else {
                        $errors['photo'] = 'Upload failed';
                    }
                } else {
                    $errors['photo'] = 'File type must be png/jpg/jpeg/gif';
                }
            } else {
                $errors['photo'] = 'File size must be below 500kb';
            }
        } else {
            $errors['photo'] = 'File upload error';
        }
    }
}
?>

<div class="header">
    <h1>Add New Book</h1>
</div>

<div class="content-section">
    <?php
    // Fetch categories
    $catQuery = "SELECT * FROM categories ORDER BY name ASC";
    $catResult = mysqli_query($conn, $catQuery);
    ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>


    <form method="POST" action="" enctype="multipart/form-data">
        <div class="add-book">
            <div class="form-group">
                <label>Book Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($title ?? ''); ?>">
                <span class="error-msg">
                    <?php echo $errors['title'] ?? ''; ?>
                </span>
            </div>

            <div class="form-group">
                <label>Author</label>
                <input type="text" name="author" value="<?php echo htmlspecialchars($author ?? ''); ?>">
                 <span class="error-msg">
                    <?php echo $errors['author'] ?? ''; ?>
                </span>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id">
                    <option value="">-- Select Category --</option>
                    <?php while ($category = mysqli_fetch_assoc($catResult)) { ?>
                        <option value="<?php echo $category['id']; ?>" <?php if (isset($category_id) && $category_id == $category['id'])
                               echo 'selected'; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php } ?>
                </select>
                 <span class="error-msg">
                    <?php echo $errors['category'] ?? ''; ?>
                </span>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($price ?? ''); ?>">
                 <span class="error-msg">
                    <?php echo $errors['price'] ?? ''; ?>
                </span>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                 <span class="error-msg">
                    <?php echo $errors['description'] ?? ''; ?>
                </span>
            </div>

            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" value="<?php echo htmlspecialchars($quantity ?? ''); ?>">
                 <span class="error-msg">
                    <?php echo $errors['quantity'] ?? ''; ?>
                </span>
            </div>

            <div class="form-group">
                <label>Book Image</label>
                <input type="file" name="photo">
                 <span class="error-msg">
                    <?php echo $errors['photo'] ?? ''; ?>
                </span>
            </div>

            <button type="submit">Add Book</button>
        </div>
    </form>
</div>