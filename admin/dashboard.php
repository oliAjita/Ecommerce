<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}

// Determine current page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Handle logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: /ECOMMERCE/user/login.php");
    exit();
}

// Handle add book
if ($page == 'add_book' && $_SERVER["REQUEST_METHOD"] == "POST") {
    $title =
        $author =
        $price =
        $description = '';

    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];

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
                } else {
                    echo 'Upload failed';
                }
            } else {
                echo 'File type must be png/jpg/jpeg/gif';
            }
        } else {
            echo 'File size must be below 500kb';
        }
    } else {
        echo 'File upload Error';
    }
}

// Handle delete product
if ($page == 'products' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $book_id = intval($_GET['id']);
    $query = "DELETE FROM books WHERE id = $book_id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Product deleted successfully!');</script>";
    }
}

// Handle delete user
if ($page == 'users' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $query = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('User deleted successfully!');</script>";
    }
}

// Get all books for products page
$books = [];
if ($page == 'products') {
    $query = "SELECT * FROM books ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get all users for users page
$users = [];
if ($page == 'users') {
    $query = "SELECT * FROM users ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/ECOMMERCE/assets/css/admin/dashboard.css" />
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav class="nav-links">
                <a href="dashboard.php?page=home" class="<?php echo $page == 'home' ? 'active' : ''; ?>">📊 Home</a>
                <a href="dashboard.php?page=add_book" class="<?php echo $page == 'add_book' ? 'active' : ''; ?>">➕ Add
                    Book</a>
                <a href="dashboard.php?page=products" class="<?php echo $page == 'products' ? 'active' : ''; ?>">📚
                    Products</a>
                <a href="dashboard.php?page=users" class="<?php echo $page == 'users' ? 'active' : ''; ?>">👥 Users</a>
                <div class="logout-btn">
                    <a href="dashboard.php?action=logout">🚪 Logout</a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <?php if ($page == 'home'): ?>
                <div class="header">
                    <h1>Welcome to Admin Dashboard</h1>
                    <p>Manage your bookstore from here</p>
                </div>
                <div class="content-section">
                    <h2>Quick Stats</h2>
                    <p>Use the sidebar to navigate and manage your store.</p>
                </div>

            <?php elseif ($page == 'add_book'): ?>
                <div class="header">
                    <h1>Add New Book</h1>
                </div>
                <div class="content-section">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="add-book">
                            <div class="form-group">
                                <label>Book Title</label>
                                <input type="text" name="title"
                                    value="<?php echo isset($_POST['title']) ? $_POST['title'] : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Author</label>
                                <input type="text" name="author"
                                    value="<?php echo isset($_POST['author']) ? $_POST['author'] : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" step="0.01" name="price"
                                    value="<?php echo isset($_POST['price']) ? $_POST['price'] : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description"
                                    value="<?php echo isset($_POST['description']) ? $_POST['description'] : '' ?>"
                                    required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Book Image</label>
                                <input type="file" name="photo" required>
                            </div>
                            <button type="submit">Add Book</button>
                        </div>
                    </form>
                </div>

            <?php elseif ($page == 'products'): ?>
                <div class="header">
                    <h1>Manage Products</h1>
                    <a href="dashboard.php?page=add_book" style="text-decoration: none;"><button
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 15px; border: none; border-radius: 6px; cursor: pointer;">+
                            Add New Product</button></a>
                </div>
                <div class="content-section">
                    <?php if (count($books) > 0): ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Price</th>
                                        <th>Image</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($books as $book): ?>
                                        <tr>
                                            <td><?php echo $book['id']; ?></td>
                                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                                            <td>Rs <?php echo number_format($book['price'], 2); ?></td>
                                            <td>
                                                <?php if (!empty($book['image'])): ?>
                                                    <img src="images/<?php echo htmlspecialchars($book['image']); ?>" alt="Book"
                                                        class="image-preview">
                                                <?php else: ?>
                                                    <span>No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($book['created_at'])); ?></td>
                                            <!-- <td>
                                                <a href="dashboard.php?page=products&action=delete&id=<?php echo $book['id']; ?>"
                                                    class="action-btn btn-delete"
                                                    onclick="return confirm('Delete this product?');">Delete</a>
                                            </td> -->

                                            <td>
                                                <!-- Edit Button -->
                                                <a href="edit_product.php?id=<?php echo $book['id']; ?>"
                                                    class="action-btn btn-edit">Edit</a>

                                                <!-- Delete Button -->
                                                <a href="dashboard.php?page=products&action=delete&id=<?php echo $book['id']; ?>"
                                                    class="action-btn btn-delete"
                                                    onclick="return confirm('Delete this product?');">Delete</a>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-message">
                            <p>No products found. <a href="dashboard.php?page=add_book">Add your first product</a></p>
                        </div>
                    <?php endif; ?>
                </div>

            <?php elseif ($page == 'users'): ?>
                <div class="header">
                    <h1>Manage Users</h1>
                </div>
                <div class="content-section">
                    <?php if (count($users) > 0): ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <!-- <th>Phone</th> -->
                                        <!-- <th>Address</th> -->
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo htmlspecialchars(string: $user['role']); ?></td>


                                            <!-- <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td> -->
                                            <!-- <td><?php echo htmlspecialchars(substr($user['address'] ?? '', 0, 50)); ?></td> -->
                                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <a href="dashboard.php?page=users&action=delete&id=<?php echo $user['id']; ?>"
                                                    class="action-btn btn-delete"
                                                    onclick="return confirm('Delete this user?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-message">
                            <p>No users found.</p>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </main>
    </div>
</body>

</html>