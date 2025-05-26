<?php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$total_lawyers = 0;
$pending_bookings = 0;
$total_clients = 0; 

$stmt_lawyers = $conn->prepare("SELECT COUNT(*) AS count FROM lawyers");
$stmt_lawyers->execute();
$result_lawyers = $stmt_lawyers->get_result();
$total_lawyers = $result_lawyers->fetch_assoc()['count'];
$stmt_lawyers->close();

$stmt_pending_bookings = $conn->prepare("SELECT COUNT(*) AS count FROM appointments WHERE status = 'pending'");
$stmt_pending_bookings->execute();
$result_pending_bookings = $stmt_pending_bookings->get_result();
$pending_bookings = $result_pending_bookings->fetch_assoc()['count'];
$stmt_pending_bookings->close();

$stmt_total_clients = $conn->prepare("SELECT COUNT(*) AS count FROM users WHERE role_id = 1"); 
$stmt_total_clients->execute();
$result_total_clients = $stmt_total_clients->get_result();
$total_clients = $result_total_clients->fetch_assoc()['count'];
$stmt_total_clients->close();

$conn->close();
?>

<h2 class="mb-4 text-success"><i class="fas fa-user-shield me-2"></i>Admin Dashboard</h2>

<p class="lead">Welcome, **<?php echo htmlspecialchars($_SESSION['username']); ?>**! Here's a quick overview:</p>

<div class="row g-4 mb-4 admin-dashboard-stats">
    <div class="col-md-4">
        <div class="stat-box">
            <h3><i class="fas fa-user-tie me-2"></i>Total Lawyers</h3>
            <p><?php echo $total_lawyers; ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <h3><i class="fas fa-hourglass-half me-2"></i>Pending Bookings</h3>
            <p><?php echo $pending_bookings; ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <h3><i class="fas fa-users me-2"></i>Total Clients</h3>
            <p><?php echo $total_clients; ?></p>
        </div>
    </div>
</div>

<h3 class="mb-3 text-secondary"><i class="fas fa-cogs me-2"></i>Admin Panel Quick Links:</h3>
<div class="list-group">
    <a href="<?php echo BASE_URL; ?>admin/manage_lawyers.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        <span><i class="fas fa-balance-scale me-2"></i>Manage Lawyers</span>
        <i class="fas fa-chevron-right"></i>
    </a>
    <a href="<?php echo BASE_URL; ?>admin/manage_bookings.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        <span><i class="fas fa-calendar-check me-2"></i>Manage Client Bookings</span>
        <i class="fas fa-chevron-right"></i>
    </a>
    <a href="<?php echo BASE_URL; ?>admin/manage_users.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users-cog me-2"></i>View All Users</span>
        <i class="fas fa-chevron-right"></i>
    </a>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>