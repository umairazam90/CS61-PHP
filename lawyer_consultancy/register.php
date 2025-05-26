<?php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/header.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $fullname = trim($_POST['fullname']);
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($fullname)) {
        $message = "All fields are required.";
        $message_type = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $message_type = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
        $message_type = "error";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt_check = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $message = "Username or Email already exists.";
            $message_type = "error";
        } else {
            $stmt_role = $conn->prepare("SELECT role_id FROM roles WHERE role_name = 'client'");
            $stmt_role->execute();
            $result_role = $stmt_role->get_result();
            $role_data = $result_role->fetch_assoc();
            $client_role_id = $role_data['role_id'];
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password, email, fullname, role_id) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("ssssi", $username, $hashed_password, $email, $fullname, $client_role_id);

            if ($stmt_insert->execute()) {
                $message = "Registration successful! You can now log in.";
                $message_type = "success";
                header("Location: " . BASE_URL . "login.php?registration=success");
                exit();
            } else {
                $message = "Error: " . $stmt_insert->error;
                $message_type = "error";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
?>

<div class="card shadow-sm p-4">
    <h2 class="card-title text-center mb-4 text-primary"><i class="fas fa-user-plus me-2"></i>Register for an Account</h2>

    <?php if ($message): ?>
        <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name:</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-4">
            <label for="confirm_password" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">Register</button>
        </div>
    </form>

    <p class="text-center mt-3">Already have an account? <a href="<?php echo BASE_URL; ?>login.php" class="text-decoration-none">Login here</a>.</p>
</div>

<?php
$conn->close();
require_once __DIR__ . '/includes/footer.php';
?>