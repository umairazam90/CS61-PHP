<?php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$users = [];
$stmt = $conn->prepare("SELECT u.user_id, u.username, u.email, u.fullname, r.role_name, u.created_at
                        FROM users u
                        JOIN roles r ON u.role_id = r.role_id
                        ORDER BY u.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();
$conn->close();
?>

<h2 class="mb-4 text-success"><i class="fas fa-users-cog me-2"></i>All Registered Users</h2>

<?php if (!empty($users)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered On</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><span class="badge <?php echo ($user['role_name'] == 'admin') ? 'bg-danger' : 'bg-primary'; ?>"><?php echo htmlspecialchars(ucfirst($user['role_name'])); ?></span></td>
                        <td><?php echo date('M d, Y h:i A', strtotime($user['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>No Users Found!</h4>
        <p>There are no registered users yet.</p>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>