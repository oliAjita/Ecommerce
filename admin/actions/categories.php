<?php


// Fetch categories
$categoryQuery = "SELECT * FROM categories ORDER BY id DESC";
$categoryResult = mysqli_query($conn, $categoryQuery);
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);
// DELETE CATEGORY
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $deleteQuery = "DELETE FROM categories WHERE id = $id";
    mysqli_query($conn, $deleteQuery);

    header("Location: dashboard.php?page=categories");
    exit;
}


                $errors = [];

                // Handle form submission
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $name = trim($_POST['name']);

                    if (empty($name)) {
                        $errors['name'] = "Category name is required";
                    } else {
                        // Check if category already exists
                        $checkQuery = "SELECT * FROM categories WHERE name='$name'";
                        $checkResult = mysqli_query($conn, $checkQuery);

                        if (mysqli_num_rows($checkResult) > 0) {
                            $errors['name'] = "Category already exists";
                        }
                    }

                    // If no errors, insert category
                    if (empty($errors)) {
                        $insertQuery = "INSERT INTO categories (name) VALUES ('$name')";

                        if (mysqli_query($conn, $insertQuery)) {
                            echo "<script>alert('Category Added Successfully'); 
                window.location='dashboard.php?page=categories';</script>";
                            exit;
                        } else {
                            $errors['name'] = "Database error: " . mysqli_error($conn);
                        }
                    }
                }

                // Fetch all categories to display
                $catQuery = "SELECT * FROM categories ORDER BY id DESC";
                $catResult = mysqli_query($conn, $catQuery);
                $categories = mysqli_fetch_all($catResult, MYSQLI_ASSOC);
                ?>

                <div class="header">
                    <h1>Add New Category</h1>
                </div>

                <div class="content-section">
                    <form method="POST">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="name"
                                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                            <?php
                            if (isset($errors['name'])) {
                                echo "<p style='color:red'>{$errors['name']}</p>";
                            }
                            ?>
                        </div>

                        <button type="submit" style="
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
            ">Add Category</button>
                    </form>

                    <!-- Categories List Table -->
                    <h2 style="margin-top:30px;">Existing Categories</h2>
                    <?php if (count($categories) > 0): ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td><?php echo $category['id']; ?></td>
                                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                                            <td>
                                                <a href="dashboard.php?page=categories&action=delete&id=<?php echo $category['id']; ?>"
                                                    class="action-btn btn-delete"
                                                    onclick="return confirm('Delete this category?');">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No categories found.</p>
                    <?php endif; ?>

                </div>