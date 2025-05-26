<?php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';
$stmt = $conn->prepare("SELECT username, email, fullname FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

if (!$user_data) {
    $message = "User data not found.";
    $message_type = "error";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fullname = trim($_POST['fullname']);
    $new_email = trim($_POST['email']);
    $new_username = trim($_POST['username']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
        $message_type = "error";
    } elseif (empty($new_fullname) || empty($new_email) || empty($new_username)) {
        $message = "Full Name, Username, and Email are required.";
        $message_type = "error";
    } else {
        $stmt_check_unique = $conn->prepare("SELECT user_id FROM users WHERE (username = ? OR email = ?) AND user_id != ?");
        $stmt_check_unique->bind_param("ssi", $new_username, $new_email, $user_id);
        $stmt_check_unique->execute();
        $stmt_check_unique->store_result();
        if ($stmt_check_unique->num_rows > 0) {
            $message = "Username or Email already taken by another account.";
            $message_type = "error";
        } else {
            $sql = "UPDATE users SET fullname = ?, email = ?, username = ? WHERE user_id = ?";
            $params = "sssi";
            $values = [&$new_fullname, &$new_email, &$new_username, &$user_id];

            if (!empty($new_password)) {
                $stmt_verify_password = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
                $stmt_verify_password->bind_param("i", $user_id);
                $stmt_verify_password->execute();
                $result_verify = $stmt_verify_password->get_result();
                $user_password_hash = $result_verify->fetch_assoc()['password'];
                $stmt_verify_password->close();

                if (!password_verify($current_password, $user_password_hash)) {
                    $message = "Current password is incorrect.";
                    $message_type = "error";
                } elseif ($new_password !== $confirm_new_password) {
                    $message = "New passwords do not match.";
                    $message_type = "error";
                } elseif (strlen($new_password) < 6) {
                    $message = "New password must be at least 6 characters long.";
                    $message_type = "error";
                } else {
                    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET fullname = ?, email = ?, username = ?, password = ? WHERE user_id = ?";
                    $params = "ssssi";
                    $values = [&$new_fullname, &$new_email, &$new_username, &$hashed_new_password, &$user_id];
                }
            }

            if (!$message) {
                $stmt_update = $conn->prepare($sql);
                $stmt_update->bind_param($params, ...$values);

                if ($stmt_update->execute()) {
                    $message = "Profile updated successfully!";
                    $message_type = "success";
                    $_SESSION['username'] = $new_username;
                    $stmt_re_fetch = $conn->prepare("SELECT username, email, fullname FROM users WHERE user_id = ?");
                    $stmt_re_fetch->bind_param("i", $user_id);
    $stmt_re_fetch->execute();
                    $result_re_fetch = $stmt_re_fetch->get_result();
                    $user_data = $result_re_fetch->fetch_assoc();
                    $stmt_re_fetch->close();
                } else {
                    $message = "Error updating profile: " . $stmt_update->error;
                    $message_type = "error";
                }
                $stmt_update->close();
            }
        }
        $stmt_check_unique->close();
    }
}
$conn->close();
?>

<h2 class="mb-4 text-primary"><i class="fas fa-user-circle me-2"></i>My Profile</h2>

<?php if ($message): ?>
    <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm p-4">
    <form action="profile.php" method="POST">
        <h3 class="mb-3 text-secondary">Account Information</h3>
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name:</label>
            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user_data['fullname'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username'] ?? ''); ?>" required>
        </div>
        <div class="mb-4">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
        </div>

        <h3 class="mb-3 text-secondary">Change Password (Optional)</h3>
        <p class="text-muted">Leave password fields blank if you don't want to change your password.</p>
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password:</label>
            <input type="password" class="form-control" id="current_password" name="current_password">
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>
        <div class="mb-4">
            <label for="confirm_new_password" class="form-label">Confirm New Password:</label>
            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password">
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Update Profile</button>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>