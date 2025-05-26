<?php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$pending_appointments_count = 0;
$confirmed_appointments_count = 0;
$total_lawyers_available = 0;

$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM appointments WHERE user_id = ? AND status = 'pending'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pending_appointments_count = $row['count'];
}
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM appointments WHERE user_id = ? AND status = 'confirmed'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $confirmed_appointments_count = $row['count'];
}
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM lawyers WHERE is_available = TRUE");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_lawyers_available = $row['count'];
}
$stmt->close();


$conn->close();
?>

<h2 class="mb-4 text-primary"><i class="fas fa-tachometer-alt me-2"></i>Client Dashboard</h2>

<p class="lead">Welcome back,<?php 
$display_username = $_SESSION['username'];
if (strpos($display_username, '@gmail.com') !== false) {
    $display_username = str_replace('@gmail.com', '', $display_username);
}
echo htmlspecialchars($display_username); 
?>**!</p>

<div class="row g-4 mb-4 admin-dashboard-stats">
    <div class="col-md-4">
        <div class="stat-box">
            <h3><i class="fas fa-clock me-2"></i>Pending Appointments</h3>
            <p><?php echo $pending_appointments_count; ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <h3><i class="fas fa-check-circle me-2"></i>Confirmed Appointments</h3>
            <p><?php echo $confirmed_appointments_count; ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <h3><i class="fas fa-users me-2"></i>Available Lawyers</h3>
            <p><?php echo $total_lawyers_available; ?></p>
        </div>
    </div>
</div>

<h3 class="mb-3 text-secondary"><i class="fas fa-bolt me-2"></i>Quick Actions:</h3>
<div class="list-group">
    <a href="<?php echo BASE_URL; ?>client/view_lawyers.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        <span><i class="fas fa-gavel me-2"></i>Browse Lawyers</span>
        <i class="fas fa-chevron-right"></i>
    </a>
    <a href="<?php echo BASE_URL; ?>client/view_appointments.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        <span><i class="fas fa-calendar-check me-2"></i>View My Appointments</span>
        <i class="fas fa-chevron-right"></i>
    </a>
    <a href="<?php echo BASE_URL; ?>client/profile.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        <span><i class="fas fa-user-circle me-2"></i>Update My Profile</span>
        <i class="fas fa-chevron-right"></i>
    </a>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>