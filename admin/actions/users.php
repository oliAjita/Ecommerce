<?php // Get all users for users page
$users = [];
if ($page == 'users') {
    $query = "SELECT * FROM users ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>
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
                                        <!-- <th>Actions</th> -->
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
                                            <!-- <td>
                                                <a href="dashboard.php?page=users&action=delete&id=<?php echo $user['id']; ?>"
                                                    class="action-btn btn-delete"
                                                    onclick="return confirm('Delete this user?');">Delete</a>
                                            </td> -->
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
