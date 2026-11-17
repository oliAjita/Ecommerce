<?php

// Get all books for products page
$books = [];
if ($page == 'products') {
    $query = "SELECT * FROM books ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Handle delete product
if ($page == 'products' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $book_id = intval($_GET['id']);
    $query = "DELETE FROM books WHERE id = $book_id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Product deleted successfully!');</script>";
    }
}
?>

<div class="header">
                    <h1>Manage Products</h1>
                    <a href="dashboard.php?page=add_book" style="text-decoration: none;"><button
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 15px; border: none; border-radius: 6px; cursor: pointer;">+
                            Add New Book</button></a>
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