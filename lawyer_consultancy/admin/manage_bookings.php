<?php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id']) && isset($_POST['new_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['new_status'];

    if (!in_array($new_status, ['pending', 'confirmed', 'cancelled', 'completed'])) {
        $message = "Invalid status provided.";
        $message_type = "danger";
    } else {
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ?");
        $stmt->bind_param("si", $new_status, $appointment_id);
        if ($stmt->execute()) {
            $message = "Appointment status updated successfully.";
            $message_type = "success";
        } else {
            $message = "Error updating status: " . $stmt->error;
            $message_type = "danger";
        }
        $stmt->close();
    }
}

$appointments = [];
$stmt = $conn->prepare("SELECT a.appointment_id, u.fullname AS client_name, u.email AS client_email,
                        l.name AS lawyer_name, l.specialization,
                        a.appointment_date, a.appointment_time, a.description, a.status, a.booked_at
                        FROM appointments a
                        JOIN users u ON a.user_id = u.user_id
                        JOIN lawyers l ON a.lawyer_id = l.lawyer_id
                        ORDER BY a.booked_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();
$conn->close();
?>

<h2 class="mb-4 text-success"><i class="fas fa-book-open me-2"></i>Manage Client Bookings</h2>

<?php if ($message): ?>
    <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php if (!empty($appointments)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="table-success">
                <tr>
                    <th>Booking ID</th>
                    <th>Client Name</th>
                    <th>Client Email</th>
                    <th>Lawyer</th>
                    <th>Specialization</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Description</th>
                    <th>Current Status</th>
                    <th>Change Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['client_email']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['lawyer_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['specialization']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></td>
                        <td><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></td>
                        <td><?php echo htmlspecialchars($appointment['description']); ?></td>
                        <td>
                            <span class="status-<?php echo strtolower($appointment['status']); ?>">
                                <?php echo htmlspecialchars(ucfirst($appointment['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <form action="manage_bookings.php" method="POST">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                                <select name="new_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="pending" <?php echo ($appointment['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo ($appointment['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="cancelled" <?php echo ($appointment['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    <option value="completed" <?php echo ($appointment['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>No Bookings Found!</h4>
        <p>There are no appointments to manage at the moment.</p>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>